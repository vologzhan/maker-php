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
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;

            INSERT INTO directory (id, path, type, project_id, parent_id) VALUES
                  (1, '/tmp/app', null, null, null),
                  (2, '/tmp/app/src', null, null, 1);
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/app/project.maker', 1);
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
    }
}
