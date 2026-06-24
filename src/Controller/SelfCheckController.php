<?php declare(strict_types=1);

namespace App\Controller;

use App\Response\SuccessResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', methods: ['GET'])]
final readonly class SelfCheckController
{
    public function __invoke(): SuccessResponse
    {
        return new SuccessResponse();
    }
}
