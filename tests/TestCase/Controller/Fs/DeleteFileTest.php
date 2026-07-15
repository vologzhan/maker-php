<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Controller\Fs\DeleteFile;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see DeleteFile
 */
final class DeleteFileTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createFile('/tmp/app/Controller.php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;

            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/app/Controller.php', null);
            INSERT INTO project (id, dir_id) VALUES (1, null);
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, null, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('DELETE', '/api/file/1')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "success":true
                }
                JSON
            );

        self::assertFileDoesNotExist('/tmp/app/Controller.php');

        $this->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'directory_id'],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
            ], 'SELECT * FROM controller');
    }
}
