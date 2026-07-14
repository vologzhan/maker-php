<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Controller\Fs\GetFsTree;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetFsTree
 */
final class GetFsTreeTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app')
            ->createDir('/tmp/app/src')
            ->createFile('/tmp/app/project.maker');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('GET', '/api/fs')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "app",
                  "dirs": [
                    {
                      "id": 2,
                      "name": "src",
                      "dirs": [],
                      "files": []
                    }
                  ],
                  "files": [
                    {
                      "id": 1,
                      "name": "project.maker"
                    }
                  ]
                }
                JSON
            );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'parent_id'],
                [1, '/tmp/app', null],
                [2, '/tmp/app/src', 1],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/project.maker', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'dir_id'],
                [1, 1],
            ], 'SELECT * FROM project');
    }
}
