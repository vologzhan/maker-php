<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs\CreateFile;

use App\Controller\Fs\CreateFile;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateFile
 */
final class CreateFileTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app')
            ->createDir('/tmp/app');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            INSERT INTO directory (id, path, parent_id) VALUES (1, '/tmp/app', null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/file',
                <<<JSON
                {
                  "directoryId": 1,
                  "name": "file.txt",
                  "type": null
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "file.txt"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/app/file.txt', '');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/file.txt', 1],
            ], 'SELECT * FROM file');
    }
}
