<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Controller\Fs\GetTree;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetTree
 */
final class GetTreeTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE dir RESTART IDENTITY CASCADE;
            INSERT INTO dir (id, path, parent_id) VALUES (1, '/tmp/maker-php', null);
            INSERT INTO dir (id, path, parent_id) VALUES (2, '/tmp/maker-php/src', 1);
            INSERT INTO dir (id, path, parent_id) VALUES (3, '/tmp/maker-php/src/bad_dir', 2);
            INSERT INTO file (id, path, dir_id) VALUES (1, '/tmp/maker-php/project.maker', 1);
            SQL
        );
    }

    public function test(): void
    {
        $this->request('GET', '/api/fs')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "dirs": [
                    {
                     "id": 1,
                      "name": "maker-php",
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
                  ]
                }
                JSON
            );
    }
}
