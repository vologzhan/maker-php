<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Field;
use App\Entity\Project;
use App\Entity\Response;
use App\Enum\Type;
use App\Repository\FieldRepository;
use App\Repository\ProjectRepository;
use App\Repository\ResponseRepository;
use App\Request\Project\IndexRequest;
use App\Response\Project\ProjectItemResponse;
use App\Serializer\ProjectSerializer;
use App\Service\Php\PhpParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final readonly class IndexService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ResponseRepository $responseRepository,
        private FieldRepository $fieldRepository,
        private PhpParser $phpParser,
        private EntityManagerInterface $em,
        private ProjectSerializer $projectSerializer,
    ) {}

    public function __invoke(IndexRequest $request): ProjectItemResponse
    {
        if (!is_dir($request->path)) {
            throw new BadRequestException("Directory does not exist '$request->path'");
        }

        $project = new Project()
            ->setPath($request->path);

        $this->projectRepository->save($project);

        $this->scanResponsesRecursive($project);
        $this->scanControllersRecursive($project);

        $this->em->flush();

        return $this->projectSerializer->projectResponseOld($project);
    }

    private function scanControllersRecursive(Project $project): void
    {
        $this->scanDirRecursive(
            project: $project,
            dir: $project->getPath() . '/src/Controller',
            fileCallback: [$this, 'indexControllerFile'],
        );
    }

    private function scanResponsesRecursive(Project $project): void
    {
        $this->scanDirRecursive(
            project: $project,
            dir: $project->getPath() . '/src/Response',
            fileCallback: [$this, 'indexResponseFile'],
        );

        $responseMap = $project->getResponsesMap();

        foreach ($project->getResponses() as $response) {
            foreach ($response->getFields() as $field) {
                if ($field->getType() === Type::Object) {
                    $object = $responseMap[$field->getPhpType()] ?? null;
                    $field->setObject($object);
                }

                $this->fieldRepository->save($field);
            }
        }
    }

    private function scanDirRecursive(Project $project, string $dir, callable $fileCallback): void
    {
        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;

            if (is_dir($path)) {
                $this->scanDirRecursive($project, $path, $fileCallback);
                continue;
            }

            $fileCallback($project, $path);
        }
    }

    private function indexResponseFile(Project $project, string $filepath): void
    {
        $file = $this->phpParser->parseFile($filepath);
        $class = $file->classes[0];
        $construct = $class->method('__construct');

        $response = new Response()
            ->setName($class->name->value)
            ->setClassName($class->fqn)
            ->setFilepath($filepath);

        $project->addResponse($response);

        $this->responseRepository->save($response);

        foreach ($construct->params as $param) {
            $isArray = $param->type->value === 'array';

            $phpType = $isArray ? $param->annotationVar->value->value : $param->type->value;
            $type = match ($phpType) {
                'string' => Type::String,
                'int' => Type::Integer,
                'bool' => Type::Boolean,
                default => Type::Object,
            };

            $field = new Field()
                ->setName($param->name->value)
                ->setType($type)
                ->setPhpType($phpType)
                ->setIsArray($isArray)
                ->setIsNullable($param->nullable)
                ->setObject(null); // устанавливается после анализа всех респонсов

            $response->addField($field);
        }
    }
}
