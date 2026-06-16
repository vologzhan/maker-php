<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project;

use App\Controller\Project\IndexController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see IndexController
 */
final class IndexControllerTest extends ApiTestCase
{
    public function setUp(): void
    {
        $this->connectionPsql()->execute('TRUNCATE TABLE project RESTART IDENTITY CASCADE');
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/project/index', body: <<<JSON
                {
                  "path": "/app/tests/Fixtures/maker-php"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "id": 1,
                  "name": "maker-php",
                  "path": "/app/tests/Fixtures/maker-php",
                  "controllers": [
                    {
                      "id": 1,
                      "name": "Index",
                      "method": "POST",
                      "path": "/api/project/index"
                    },
                    {
                      "id": 2,
                      "name": "Self check",
                      "method": "GET",
                      "path": "/"
                    }
                  ]
                }
                JSON
            );

        $this
            ->connectionPsql()
            ->assertEqualRow(
                [1, 'maker-php', '/app/tests/Fixtures/maker-php'],
                'SELECT id, name, path FROM project'
            )
            ->assertEquals(
                [
                    [1, 1, 'Index', 'POST', '/api/project/index', '/app/tests/Fixtures/maker-php/src/Controller/Project/IndexController.php'],
                    [2, 1, 'Self check', 'GET', '/', '/app/tests/Fixtures/maker-php/src/Controller/SelfCheckController.php'],
                ],
                'SELECT id, project_id, name, method, path, filepath FROM controller'
            );
    }
}
