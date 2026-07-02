<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project;

use App\Controller\Project\IndexDirectoryController;
use App\Tests\Infrastructure\ApiTestCase;

/** @see IndexDirectoryController */
final class IndexDirectoryTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute('TRUNCATE TABLE project RESTART IDENTITY CASCADE');
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/project/index', <<<JSON
                {
                  "path": "/app/tests/fixtures/maker-php"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "id": 1,
                  "name": "maker-php"
                }
                JSON
            );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'name'],
                [1, 'maker-php'],
            ], 'SELECT * FROM project')
            ->assertEquals([
                ['id', 'path', 'type', 'project_id', 'parent_id'],
                [1, '/app/tests/fixtures/maker-php', 'project', 1, null],
                [2, '/app/tests/fixtures/maker-php/src', null, 1, 1],
                [3, '/app/tests/fixtures/maker-php/src/Controller', null, 1, 2],
                [4, '/app/tests/fixtures/maker-php/src/Controller/Project', null, 1, 3],
                [5, '/app/tests/fixtures/maker-php/src/Response', null, 1, 2],
                [6, '/app/tests/fixtures/maker-php/src/Response/Controller', null, 1, 5],
                [7, '/app/tests/fixtures/maker-php/src/Response/Project', null, 1, 5],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/app/tests/fixtures/maker-php/src/Controller/Project/IndexController.php', 4],
                [2, '/app/tests/fixtures/maker-php/src/Controller/SelfCheckController.php', 3],
                [3, '/app/tests/fixtures/maker-php/src/Response/Controller/ControllerItem.php', 6],
                [4, '/app/tests/fixtures/maker-php/src/Response/Project/ProjectResponse.php', 7],
                [5, '/app/tests/fixtures/maker-php/src/Response/SuccessResponse.php', 5],
            ], 'SELECT * FROM file');
    }
}
