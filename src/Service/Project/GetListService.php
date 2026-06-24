<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Repository\ProjectRepository;
use App\Response\Project\ListResponse;
use App\Serializer\ProjectSerializer;

final readonly class GetListService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectSerializer $projectSerializer,
    ) {}

    public function __invoke(): ListResponse
    {
        $projects = $this->projectRepository->findAll();

        return $this->projectSerializer->listResponse($projects);
    }
}
