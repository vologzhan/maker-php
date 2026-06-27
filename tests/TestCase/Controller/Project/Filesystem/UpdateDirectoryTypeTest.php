<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project\Filesystem;

use App\Controller\Project\Filesystem\UpdateDirectoryTypeController;
use App\Tests\Infrastructure\ApiTestCase;

/** @see UpdateDirectoryTypeController */
final class UpdateDirectoryTypeTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute(<<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');

            INSERT INTO directory (id, path, project_id, parent_id, type)
            VALUES (1, '/app/maker-php', 1, null, 'project'),
                   (2, '/app/maker-php/Controller', 1, 1, null);
            
            INSERT INTO file (id, path, directory_id, type)
            VALUES (1, '/app/maker-php/Controller/SelfCheckController.php', 2, null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('PUT', '/api/filesystem/directory/2/type', body: <<<JSON
                {
                  "type": "controller"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "success": true
                }
                JSON
            );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'type', 'project_id', 'parent_id'],
                [1, '/app/maker-php', 'project', 1, null],
                [2, '/app/maker-php/Controller', 'controller', 1, 1],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'type', 'directory_id'],
                [1, '/app/maker-php/Controller/SelfCheckController.php', 'controller', 2],
            ], 'SELECT * FROM file');
    }
}
