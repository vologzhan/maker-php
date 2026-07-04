<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Entity\Dir;
use App\Entity\File;
use App\Entity\Project;
use App\Repository\DirRepository;
use App\Repository\FileRepository;
use App\Repository\ProjectRepository;
use App\Request\Fs\IndexDirRequest;
use App\Response\SuccessResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/fs', methods: ['POST'])]
final readonly class IndexDir
{
    public function __construct(
        private DirRepository $dirRepository,
        private FileRepository $fileRepository,
        private ProjectRepository $projectRepository,
    ) {}

    public function __invoke(IndexDirRequest $request): SuccessResponse
    {
        $dir = $this->dirRepository->save(
            new Dir()
                ->setPath($request->path)
                ->setParent(null)
        );

        $this->indexDirRecursive($dir);

        return new SuccessResponse();
    }

    private function indexDirRecursive(Dir $dir): void
    {
        $items = scandir($dir->getPath());

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir->getPath() . '/' . $item;

            if (is_dir($path)) {
                $child = $this->dirRepository->save(
                    new Dir()
                        ->setPath($path)
                        ->setParent($dir)
                );

                $this->indexDirRecursive($child);
                continue;
            }

            $this->fileRepository->save(
                new File()
                    ->setPath($path)
                    ->setDir($dir)
            );

            if ($item === 'project.maker') {
                $this->projectRepository->save(
                    new Project()
                        ->setDir($dir)
                );
            }
        }
    }
}
