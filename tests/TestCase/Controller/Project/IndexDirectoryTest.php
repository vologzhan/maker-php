<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project;

use App\Controller\Project\IndexDirectoryController;
use App\Tests\Infrastructure\Annotation\Skip;
use App\Tests\Infrastructure\ApiTestCase;

/** @see IndexDirectoryController */
final class IndexDirectoryTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute('TRUNCATE TABLE project RESTART IDENTITY CASCADE');
    }

    #[Skip]
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
                ['id', 'path', 'project_id', 'parent_id', 'type'],
                [1, '/app/tests/fixtures/maker-php', 1, null, 'project'],
                [2, '/app/tests/fixtures/maker-php/src', 1, 1, null],
                [3, '/app/tests/fixtures/maker-php/src/Controller', 1, 2, null],
                [4, '/app/tests/fixtures/maker-php/src/Controller/Project', 1, 3, null],
                [5, '/app/tests/fixtures/maker-php/src/Response', 1, 2, null],
                [6, '/app/tests/fixtures/maker-php/src/Response/Controller', 1, 5, null],
                [7, '/app/tests/fixtures/maker-php/src/Response/Project', 1, 5, null],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'directory_id', 'type'],
                [1, '/app/tests/fixtures/maker-php/src/Controller/Project/IndexController.php', 4, null],
                [2, '/app/tests/fixtures/maker-php/src/Controller/SelfCheckController.php', 3, null],
                [3, '/app/tests/fixtures/maker-php/src/Response/Controller/ControllerItem.php', 6, null],
                [4, '/app/tests/fixtures/maker-php/src/Response/Project/ProjectResponse.php', 7, null],
                [5, '/app/tests/fixtures/maker-php/src/Response/SuccessResponse.php', 5, null],
            ], 'SELECT * FROM file');
    }
}
