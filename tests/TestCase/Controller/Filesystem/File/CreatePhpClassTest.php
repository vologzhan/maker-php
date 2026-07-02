<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Filesystem\File\CreateController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateController
 */
final class CreatePhpClassTest extends ApiTestCase
{
    protected function setUp(): void
    {
        unlink('/tmp/tests/Controller/Filesystem/File/CreatePhpClass/MyClass.php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO directory (id, path, type, project_id, parent_id) VALUES (1, '/tmp/tests/Controller/Filesystem/File/CreatePhpClass', null, 1, null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/filesystem/file', body: <<<JSON
                {
                  "directoryId": 1,
                  "name": "MyClass",
                  "type": "php_class"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "filename": "MyClass.php"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/tests/Controller/Filesystem/File/CreatePhpClass/MyClass.php',
            <<<PHP
            <?php declare(strict_types=1);

            namespace App;

            final readonly class MyClass
            {
            
            }

            PHP
        );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/tests/Controller/Filesystem/File/CreatePhpClass/MyClass.php', 1],
            ], 'SELECT * FROM file');
    }
}
