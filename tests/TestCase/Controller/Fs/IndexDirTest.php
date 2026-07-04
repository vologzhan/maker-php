<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Controller\Fs\IndexDir;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see IndexDir
 */
final class IndexDirTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createFile('/tmp/tests/Controller/Fs/IndexDirTest/maker-php/project.maker', '');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE dir RESTART IDENTITY CASCADE;
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/fs',
                <<<JSON
                {
                  "path":"/tmp/tests/Controller/Fs/IndexDirTest/maker-php"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "success": true
                }
                JSON
            );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'parent_id'],
                [1, '/tmp/tests/Controller/Fs/IndexDirTest/maker-php', null],
            ], 'SELECT * FROM dir')
            ->assertEquals([
                ['id', 'path', 'dir_id'],
                [1, '/tmp/tests/Controller/Fs/IndexDirTest/maker-php/project.maker', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'dir_id'],
                [1, 1],
            ], 'SELECT * FROM project');
    }
}
