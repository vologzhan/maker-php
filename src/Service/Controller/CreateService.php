<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Entity\Controller;
use App\Repository\ControllerRepository;
use App\Repository\DirectoryRepository;
use App\Repository\ResponseRepository;
use App\Request\Controller\CreateRequest;
use App\Response\Project\Controller\ControllerItem;
use App\Serializer\ControllerSerializer;

final readonly class CreateService
{
    public function __construct(
        private ControllerHelper $controllerHelper,
        private ControllerRepository $controllerRepository,
        private ControllerSerializer $controllerSerializer,
        private DirectoryRepository $directoryRepository,
        private ResponseRepository $responseRepository,
    ) {}

    public function __invoke(CreateRequest $request): ControllerItem
    {
        $dir = $this->directoryRepository->findById($request->directoryId);
        $response = $this->responseRepository->findByProjectAndName($dir->getProject(), 'SuccessResponse');

        $name = $this->generateUniqueName($dir);
        $className = $this->controllerHelper->nameToClassName($name);
        $filepath = sprintf('/%s/%s.php', $dir->path(), $className);
        $method = 'GET';
        $path = '';

        // todo dynamic namespace
        // todo dynamic response
        $content = <<<PHP
            <?php declare(strict_types=1);

            namespace App\Controller;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('$path', methods: ['$method'])]
            final readonly class $className
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }

            PHP;

        file_put_contents($filepath, $content);

        $controller = new Controller()
            ->setName($name)
            ->setProject($dir->getProject())
            ->setResponse($response)
            ->setPath($path)
            ->setMethod($method)
            ->setFilepath($filepath);

        $this->controllerRepository->save($controller);

        return $this->controllerSerializer->controllerItem($controller);
    }

    private function generateUniqueName($dir): string
    {
        $counter = 1;

        while (true) {
            $suffix = $counter === 1 ? '' : " $counter";
            $counter++;
            $name = sprintf('New controller%s',  $suffix);

            $isFound = false;
            foreach ($dir->getFiles() as $file) {
                $isFound = $file->name() === $name;
                if ($isFound) {
                    break;
                }
            }

            if (!$isFound) {
                return $name;
            }
        }
    }
}
