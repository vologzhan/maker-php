<?php declare(strict_types=1);

namespace App\Controller\Dir;

use App\Entity\Dir;
use App\Entity\File;
use App\Repository\DirRepository;
use App\Repository\FileRepository;
use App\Request\Dir\IndexDirRequest;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/dir/index', methods: ['POST'])]
final readonly class IndexDir
{
    public function __construct(
        private DirRepository $dirRepository,
        private FileRepository $fileRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(IndexDirRequest $request): SuccessResponse
    {
        $dir = $this->dirRepository->save(
            new Dir()
                ->setPath($request->path)
                ->setParent(null)
        );

        $this->scanDirRecursive($dir);

        $this->entityManager->flush();

        return new SuccessResponse();
    }

    private function scanDirRecursive(Dir $dir): void
    {
        $items = scandir($dir->getPath());

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir->getPath() . '/' . $item;

            if (is_file($path)) {
                $this->fileRepository->save(
                    new File()
                        ->setPath($path)
                        ->setDir($dir)
                );

                continue;
            }

            $child = $this->dirRepository->save(
                new Dir()
                    ->setPath($path)
                    ->setParent($dir)
            );

            $this->scanDirRecursive($child);
        }
    }
}
