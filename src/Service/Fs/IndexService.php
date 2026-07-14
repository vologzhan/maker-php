<?php declare(strict_types=1);

namespace App\Service\Fs;

use App\Entity\Directory;
use App\Entity\File;
use App\Repository\DirectoryRepository;
use App\Repository\FileRepository;

final readonly class IndexService
{
    public function __construct(
        private DirectoryRepository $dirRepository,
        private FileRepository $fileRepository,
    ) {}

    public function __invoke(string $path): Directory
    {
        $dir = $this->dirRepository->save(
            new Directory()
                ->setPath($path)
        );

        $this->scanDirRecursive($dir);

        return $dir;
    }

    private function scanDirRecursive(Directory $dir): void
    {
        $items = scandir($dir->getPath());

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir->getPath() . '/' . $item;

            if (is_dir($path)) {
                $child = $this->dirRepository->save(
                    new Directory()
                        ->setParent($dir)
                        ->setPath($path)
                );

                $this->scanDirRecursive($child);

                continue;
            }

            $file = $this->fileRepository->save(
                new File()
                    ->setDirectory($dir)
                    ->setPath($path)
            );

            $dir->addFile($file);
        }
    }
}
