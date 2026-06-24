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
                ['id', 'path', 'project_id', 'parent_id', 'type'],
                [1, '/app/maker-php', 1, null, 'project'],
                [2, '/app/maker-php/Controller', 1, 1, 'controller'],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'directory_id', 'type'],
                [1, '/app/maker-php/Controller/SelfCheckController.php', 2, 'controller'],
            ], 'SELECT * FROM file');
    }
}
