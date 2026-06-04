<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Infrastructure\ApiTestCase;

final class SelfCheckControllerTest extends ApiTestCase
{
    public function testCheckReturnsSuccess(): void
    {
        $this
            ->request('GET', '/')
            ->expectedHeader('Content-Type', 'application/json')
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "success": true
                }
                JSON);
    }
}
