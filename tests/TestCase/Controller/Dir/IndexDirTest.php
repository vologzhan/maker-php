<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Dir;

use App\Controller\Dir\IndexDir;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see IndexDir
 */
final class IndexDirTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createFile('/tmp/tests/Controller/Dir/IndexTest/maker-php/project.maker', '');

        $this->connectionPsql()->execute('TRUNCATE TABLE dir RESTART IDENTITY CASCADE');
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/dir/index',
                <<<JSON
                {
                  "path":"/tmp/tests/Controller/Dir/IndexTest/maker-php"
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
                [1, '/tmp/tests/Controller/Dir/IndexTest/maker-php', null],
            ], 'SELECT * FROM dir')
            ->assertEquals([
                ['id', 'path', 'dir_id'],
                [1, '/tmp/tests/Controller/Dir/IndexTest/maker-php/project.maker', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'dir_id'],
                [1, 1],
            ], 'SELECT * FROM project');
    }
}
