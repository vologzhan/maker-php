<?php declare(strict_types=1);

namespace Fixtures\Controller;

use Fixtures\Response\SuccessResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', methods: ['GET'])]
final readonly class SelfCheckController
{
    public function __invoke(): SuccessResponse
    {
        return new SuccessResponse();
    }
}
