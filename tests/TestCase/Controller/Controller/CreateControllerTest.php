<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\CreateController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateController
 */
final class CreateControllerTest extends ApiTestCase
{
    public function test(): void
    {
        $this
            ->request('POST', '/api/controller', body: <<<JSON
                {
                  "name": "Self check",
                  "path": "/",
                  "method": "GET"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "uuid": "019e98a9-592b-7988-8d91-6893b70e38c5"
                }
                JSON
            );

        $expected = $this->fixturesDir() . '/SelfCheckController.php';
        $actual = $this->tmpDir() . '/SelfCheckController.php';

        self::assertFileEquals($expected, $actual);
    }
}
