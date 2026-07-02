<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project\Filesystem;

use App\Controller\Project\Filesystem\GetTreeController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetTreeController
 */
final class GetTreeTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;

            INSERT INTO project (id, name) VALUES (1, 'maker-php');

            INSERT INTO directory (id, path, project_id, parent_id, type)
            VALUES (1, '/app/tests/fixtures/maker-php', 1, null, 'project'),
                   (2, '/app/tests/fixtures/maker-php/src', 1, 1, null),
                   (3, '/app/tests/fixtures/maker-php/src/Controller', 1, 2, 'controller');
            
            INSERT INTO file (id, path, directory_id)
            VALUES (1, '/app/tests/fixtures/maker-php/src/Controller/SelfCheckController.php', 3);

            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, null, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('GET', '/api/project/1/filesystem/tree')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "maker-php",
                  "type": "project",
                  "files": [],
                  "directories": [
                    {
                        "id": 2,
                        "name": "src",
                        "type": null,
                        "files": [],
                        "directories": [
                          {
                            "id": 3,
                            "name": "Controller",
                            "type": "controller",
                            "directories": [],
                            "files": [
                              {
                                "id": 1,
                                "name": "SelfCheckController.php",
                                "type": "controller"
                              }
                            ]
                          }
                        ]
                    }
                  ]
                }
                JSON
            );
    }
}
