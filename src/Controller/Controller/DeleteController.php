<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Response\SuccessResponse;
use App\Service\Php\PhpParser;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/api/controller/{uuid}', requirements: ['uuid' => Requirement::UUID_V7], methods: ['DELETE'])]
final readonly class DeleteController
{
    public function __construct(
        private PhpParser $phpParser,
    ) {}

    public function __invoke(Uuid $uuid): SuccessResponse
    {
        $file = $this->phpParser->parseFile('/tmp/SelfCheckController.php'); // todo hardcode
        unlink($file->path);

        return new SuccessResponse();
    }
}
