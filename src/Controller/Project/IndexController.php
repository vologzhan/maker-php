<?php declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Controller;
use App\Entity\Project;
use App\Repository\ControllerRepository;
use App\Repository\ProjectRepository;
use App\Request\Project\IndexRequest;
use App\Response\Project\ProjectResponse;
use App\Serializer\ProjectSerializer;
use App\Service\Controller\ControllerService;
use App\Service\Php\PhpParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project/index', methods: ['POST'])]
final readonly class IndexController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ControllerRepository $controllerRepository,
        private PhpParser $phpParser,
        private EntityManagerInterface $em,
        private ProjectSerializer $projectSerializer,
        private ControllerService $controllerService,
    ) {}

    public function __invoke(IndexRequest $request): ProjectResponse
    {
        if (!is_dir($request->path)) {
            throw new BadRequestException("Directory does not exist '$request->path'");
        }

        $project = new Project()
            ->setName(basename($request->path))
            ->setPath($request->path);

        $this->projectRepository->save($project);

        $controllersDir = $project->getPath() . '/src/Controller';
        $this->indexControllerDirRecursive($project, $controllersDir);

        $this->em->flush();

        return $this->projectSerializer->projectResponse($project);
    }

    private function indexControllerDirRecursive(Project $project, string $dir): void
    {
        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;

            if (is_dir($path)) {
                $this->indexControllerDirRecursive($project, $path);
                continue;
            }

            $this->indexControllerFile($project, $path);
        }
    }

    private function indexControllerFile(Project $project, string $filepath): void
    {
        $file = $this->phpParser->parseFile($filepath);
        $class = $file->classes[0];
        $className = $class->name;
        $routeAttribute = $class->attribute(Route::class);

        $name = $this->controllerService->classNameToName($className->value);

        $controller = new Controller()
            ->setName($name)
            ->setPath($routeAttribute->args[0]->value->value)
            ->setMethod($routeAttribute->args[1]->value->value[0])
            ->setFilepath($filepath);

        $project->addController($controller);

        $this->controllerRepository->save($controller);
    }
}
