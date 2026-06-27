<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Filesystem\File\DeleteController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see DeleteController
 */
final class DeleteTest extends ApiTestCase
{
    public function test(): void
    {
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/tests/Controller/Filesystem/File/DeleteTest/Controller.php', null);
            SQL
        );

        $this->filesystem()->createFile('/tmp/tests/Controller/Filesystem/File/DeleteTest/Controller.php', '<?php');

        $this
            ->request('DELETE', '/api/filesystem/file/1')
            ->expectedCode(200)
            ->expectedJsonContent('{"success":true}');

        self::assertFileDoesNotExist('/tmp/tests/Controller/Filesystem/File/DeleteTest/Controller.php');

        $this->connectionPsql()->assertEquals([
            ['id', 'path', 'directory_id'],
        ], 'SELECT * FROM file');
    }
}
