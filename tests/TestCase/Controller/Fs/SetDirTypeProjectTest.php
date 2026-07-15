<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SetDirType
 */
final class SetDirTypeProjectTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app')
            ->createDir('/tmp/app');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;

            INSERT INTO directory (id, path, parent_id) VALUES (1, '/tmp/app', null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/dir/1/type',
                <<<JSON
                {
                  "type": "project"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "project.maker"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/app/project.maker', '');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'dir_id'],
                [1, 1],
            ], 'SELECT * FROM project')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/project.maker', 1],
            ], 'SELECT * FROM file');
    }
}
