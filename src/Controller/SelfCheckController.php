<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class SelfCheckController
{
    #[Route('/', methods: ['GET'])]
    public function check(): JsonResponse
    {
        return new JsonResponse(['success' => true]);
    }
}
