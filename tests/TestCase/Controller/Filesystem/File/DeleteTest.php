<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Filesystem\File\DeleteController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see DeleteController
 */
final class DeleteTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/tests/Controller/Filesystem/File/DeleteTest/Controller.php', null);
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, null, 1);
            SQL
        );

        $this->filesystem()->createFile('/tmp/tests/Controller/Filesystem/File/DeleteTest/Controller.php', '<?php');
    }

    public function test(): void
    {
        $this
            ->request('DELETE', '/api/filesystem/file/1')
            ->expectedCode(200)
            ->expectedJsonContent('{"success":true}');

        self::assertFileDoesNotExist('/tmp/tests/Controller/Filesystem/File/DeleteTest/Controller.php');

        $this->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'directory_id'],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
            ], 'SELECT * FROM controller');
    }
}
