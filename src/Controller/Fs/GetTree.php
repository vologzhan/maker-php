<?php declare(strict_types=1);

namespace App\Controller\Fs;

use App\Repository\DirRepository;
use App\Response\Fs\TreeResponse;
use App\Serializer\FsTreeSerializer;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/fs', methods: ['GET'])]
final readonly class GetTree
{
    public function __construct(
        private DirRepository $dirRepository,
        private FsTreeSerializer $fsTreeSerializer,
    ) {}

    public function __invoke(): TreeResponse
    {
        $dirs = $this->dirRepository->findRoots();

        return $this->fsTreeSerializer->treeResponse($dirs, 2);
    }
}
