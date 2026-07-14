<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Repository\DirectoryRepository;
use App\Response\Fs\Tree\DirItem;
use App\Serializer\FsSerializer;
use App\Service\Fs\IndexService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/fs', methods: ['GET'])]
final readonly class GetTree
{
    public function __construct(
        private DirectoryRepository $dirRepository,
        private IndexService $indexService,
        private FsSerializer $fsSerializer,
        private EntityManagerInterface $entityManager,
        #[Autowire(env: 'PATH_APP')] private string $path,
    ) {}

    public function __invoke(): DirItem
    {
        $dir = $this->dirRepository->findRootOrNull();

        if ($dir === null) {
            $dir = $this->indexService->__invoke($this->path);
            $this->entityManager->flush();
        }

        return $this->fsSerializer->dirItem($dir);
    }
}
