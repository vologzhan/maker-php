<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\GetOneController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetOneController
 */
final class GetOneControllerTest extends ApiTestCase
{
    public function test(): void
    {
        $this
            ->request('GET', '/api/controller/019e98a9-592b-7988-8d91-6893b70e38c5')
            ->expectedCode(200)
            ->expectedJsonContent(<<<'JSON'
                {
                    "uuid": "019e98a9-592b-7988-8d91-6893b70e38c5",
                    "name": "Self check",
                    "method": "GET",
                    "path": "/"
                }
                JSON
            );
    }
}
