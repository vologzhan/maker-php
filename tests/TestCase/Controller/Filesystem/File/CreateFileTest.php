<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Filesystem\File\CreateController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateController
 */
final class CreateFileTest extends ApiTestCase
{
    protected function setUp(): void
    {
        unlink('/tmp/tests/Controller/Filesystem/File/CreateFileTest/file.txt');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO directory (id, path, type, project_id, parent_id) VALUES (2, '/tmp/tests/Controller/Filesystem/File/CreateFileTest', null, 1, null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/filesystem/file', body:
                <<<JSON
                {
                  "directoryId": 2,
                  "name": "file.txt"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/tests/Controller/Filesystem/File/CreateFileTest/file.txt', '');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/tests/Controller/Filesystem/File/CreateFileTest/file.txt', 2],
            ], 'SELECT * FROM file');
    }
}
