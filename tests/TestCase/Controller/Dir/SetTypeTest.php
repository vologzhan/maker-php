<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Dir;

use App\Controller\Dir\SetType;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SetType
 */
final class SetTypeTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createDir('/tmp/tests/Controller/Dir/SetDirTypeTest/maker-php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE dir RESTART IDENTITY CASCADE;
            INSERT INTO dir (id, path) VALUES (1, '/tmp/tests/Controller/Dir/SetDirTypeTest/maker-php')
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('PUT', '/api/dir/1/type', body:
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
                  "success": true
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/tests/Controller/Dir/SetDirTypeTest/maker-php', '');

        $this->connectionPsql()->assertEquals([
            ['id', 'dir_id'],
            [1, 1],
        ], 'SELECT * FROM project');
    }
}
