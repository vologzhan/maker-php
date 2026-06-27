<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller;

use App\Controller\SelfCheckController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SelfCheckController
 */
final class SelfCheckTest extends ApiTestCase
{
    public function test(): void
    {
        $this
            ->request('GET', '/api')
            ->expectedHeader('Content-Type', 'application/json')
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "success": true
                }
                JSON
            );
    }
}
