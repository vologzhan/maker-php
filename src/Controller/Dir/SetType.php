<?php declare(strict_types=1);

namespace App\Controller\Dir;

use App\Entity\Project;
use App\Repository\DirRepository;
use App\Repository\ProjectRepository;
use App\Request\Dir\SetTypeRequest;
use App\Response\SuccessResponse;
use App\Service\Filesystem\FilesystemHelper;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/dir/{id}/type', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
final readonly class SetType
{
    public function __construct(
        private DirRepository $dirRepository,
        private ProjectRepository $projectRepository,
        private FilesystemHelper $filesystemHelper,
    ) {}

    public function __invoke(SetTypeRequest $request): SuccessResponse
    {
        $dir = $this->dirRepository->findById($request->id);

        $this->projectRepository->save(
            new Project()
                ->setDir($dir)
        );

        $filepath = $this->filesystemHelper->joinPath($dir->getPath(), 'project.maker');
        $this->filesystemHelper->createFile($filepath, '');

        return new SuccessResponse();
    }
}
