<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Entity\Controller;
use App\Entity\File;
use App\Enum\FileType;
use App\Repository\ControllerRepository;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;
use App\Request\Fs\CreateFileRequest;
use App\Response\Fs\Tree\FileItem;
use App\Serializer\FsSerializer;
use App\Service\Fs\DirHelper;
use App\Service\Fs\FsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/file', methods: ['POST'])]
final readonly class CreateFile
{
    public function __construct(
        private DirectoryRepository $directoryRepository,
        private FileRepository $fileRepository,
        private ControllerRepository $controllerRepository,
        private FsHelper $fsHelper,
        private DirHelper $dirHelper,
        private EntityManagerInterface $entityManager,
        private FsSerializer $fsSerializer,
    ) {}

    public function __invoke(CreateFileRequest $request): FileItem
    {
        if ($request->type === FileType::PhpClass) {
            $filename = $request->name . '.php';
            $namespace = 'App'; // todo
            $content = <<<PHP
                <?php declare(strict_types=1);
                
                namespace $namespace;

                final readonly class $request->name
                {
                
                }

                PHP;
        } else if ($request->type === FileType::Controller) {
            $filename = $request->name . '.php';
            $namespace = 'App\Controller'; // todo
            $responseName = 'SuccessResponse'; // todo
            $path = '/';
            $method = 'GET';
            $content = <<<PHP
                <?php declare(strict_types=1);

                namespace $namespace;
                
                use App\Response\SuccessResponse;
                use Symfony\Component\Routing\Attribute\Route;
                
                #[Route('$path', methods: ['$method'])]
                final readonly class $request->name
                {
                    public function __invoke(): $responseName
                    {
                        return new $responseName();
                    }
                }

                PHP;
        } else {
            $filename = $request->name;
            $content = '';
        }

        $dir = $this->directoryRepository->findById($request->directoryId);

        $filepath = $this->fsHelper->joinPath($dir->getPath(), $filename);
        $this->fsHelper->createFile($filepath, $content);

        $file = new File()
            ->setPath($filepath)
            ->setDirectory($dir);

        if ($request->type === FileType::Controller) {
            $projectDir = $this->dirHelper->getProjectDir($dir);

            $controller = new Controller()
                ->setPath($path)
                ->setMethod($method)
                ->setProject($projectDir->getProject())
                ->setResponse(null); // todo

            $file->setController($controller);

            $this->controllerRepository->save($controller);
        }

        $this->fileRepository->save($file);

        $this->entityManager->flush();

        return $this->fsSerializer->fileItem($file);
    }

//    public function __invoke(CreateRequest $request): ControllerItem
//    {
//        $dir = $this->directoryRepository->findById($request->directoryId);
//        $response = $this->responseRepository->findByProjectAndName($dir->getProject(), 'SuccessResponse');
//
//        $name = $this->generateUniqueName($dir);
//        $className = $this->controllerHelper->nameToClassName($name);
//        $filepath = sprintf('/%s/%s.php', $dir->path(), $className);
//        $method = 'GET';
//        $path = '';
//
//        // todo dynamic namespace
//        // todo dynamic response
//        $content = <<<PHP
//            <?php declare(strict_types=1);
//
//            namespace App\Controller;
//
//            use App\Response\SuccessResponse;
//            use Symfony\Component\Routing\Attribute\Route;
//
//            #[Route('$path', methods: ['$method'])]
//            final readonly class $className
//            {
//                public function __invoke(): SuccessResponse
//                {
//                    return new SuccessResponse();
//                }
//            }
//
//            PHP;
//
//        file_put_contents($filepath, $content);
//
//        $controller = new Controller()
//            ->setName($name)
//            ->setProject($dir->getProject())
//            ->setResponse($response)
//            ->setPath($path)
//            ->setMethod($method)
//            ->setFilepath($filepath);
//
//        $this->controllerRepository->save($controller);
//
//        return $this->controllerSerializer->controllerItem($controller);
//    }
//
//    private function generateUniqueName($dir): string
//    {
//        $counter = 1;
//
//        while (true) {
//            $suffix = $counter === 1 ? '' : " $counter";
//            $counter++;
//            $name = sprintf('New controller%s',  $suffix);
//
//            $isFound = false;
//            foreach ($dir->getFiles() as $file) {
//                $isFound = $file->name() === $name;
//                if ($isFound) {
//                    break;
//                }
//            }
//
//            if (!$isFound) {
//                return $name;
//            }
//        }
//    }
}
