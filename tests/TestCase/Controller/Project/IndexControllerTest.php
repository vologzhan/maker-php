<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project;

use App\Controller\Project\IndexController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see IndexController
 */
final class IndexControllerTest extends ApiTestCase
{
    public function setUp(): void
    {
        $this->connectionPsql()->execute('TRUNCATE TABLE project RESTART IDENTITY CASCADE');
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/project/index', body: <<<JSON
                {
                  "path": "/app/tests/Fixtures/maker-php"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "id": 1,
                  "name": "maker-php",
                  "controllers": {
                    "name": "Controller",
                    "directories": [
                      {
                        "name": "Project",
                        "directories": [],
                        "files": [
                          {
                            "id": 1,
                            "name": "Index",
                            "method": "POST",
                            "path": "/api/project/index",
                            "responseId": 2
                          }
                        ]
                      }
                    ],
                    "files": [
                      {
                        "id": 2,
                        "name": "Self check",
                        "method": "GET",
                        "path": "/",
                        "responseId": 3
                      }
                    ]
                  },
                  "responses": [
                    {
                      "id": 1,
                      "name": "ControllerItem",
                      "fields": [
                        {
                          "id": 1,
                          "name": "id",
                          "type": "integer",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        },
                        {
                          "id": 2,
                          "name": "name",
                          "type": "string",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        },
                        {
                          "id": 3,
                          "name": "method",
                          "type": "string",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        },
                        {
                          "id": 4,
                          "name": "path",
                          "type": "string",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        }
                      ]
                    },
                    {
                      "id": 2,
                      "name": "ProjectResponse",
                      "fields": [
                        {
                          "id": 5,
                          "name": "id",
                          "type": "integer",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        },
                        {
                          "id": 6,
                          "name": "name",
                          "type": "string",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        },
                        {
                          "id": 7,
                          "name": "controllers",
                          "type": "object",
                          "isArray": true,
                          "isNullable": false,
                          "objectId": 1
                        }
                      ]
                    },
                    {
                      "id": 3,
                      "name": "SuccessResponse",
                      "fields": [
                        {
                          "id": 8,
                          "name": "success",
                          "type": "boolean",
                          "isArray": false,
                          "isNullable": false,
                          "objectId": null
                        }
                      ]
                    }
                  ]
                }
                JSON
            )
        ;

        $this
            ->connectionPsql()
            ->assertEqualRow(
                [1, 'maker-php', '/app/tests/Fixtures/maker-php'],
                'SELECT * FROM project'
            )
            ->assertEquals(
                [
                    [1, 'Index', '/api/project/index', 'POST', '/app/tests/Fixtures/maker-php/src/Controller/Project/IndexController.php', 1,2],
                    [2, 'Self check', '/', 'GET', '/app/tests/Fixtures/maker-php/src/Controller/SelfCheckController.php', 1, 3],
                ],
                'SELECT * FROM controller'
            )
            ->assertEquals(
                [
                    [1, 'ControllerItem', '/app/tests/Fixtures/maker-php/src/Response/Controller/ControllerItem.php', 1, 'Fixtures\Response\Controller\ControllerItem'],
                    [2, 'ProjectResponse', '/app/tests/Fixtures/maker-php/src/Response/Project/ProjectResponse.php', 1, 'Fixtures\Response\Project\ProjectResponse'],
                    [3, 'SuccessResponse', '/app/tests/Fixtures/maker-php/src/Response/SuccessResponse.php', 1, 'Fixtures\Response\SuccessResponse'],
                ],
                'SELECT * FROM response'
            )
            ->assertEquals(
                [
                    [1, 'id', 'integer', false, false,  1, null, 'int'],
                    [2, 'name', 'string', false, false,  1, null, 'string'],
                    [3, 'method', 'string', false, false,  1, null, 'string'],
                    [4, 'path', 'string', false, false,  1, null, 'string'],
                    [5, 'id', 'integer', false, false,  2, null, 'int'],
                    [6, 'name', 'string', false, false,  2, null, 'string'],
                    [7, 'controllers', 'object', true, false,  2, 1, 'Fixtures\Response\Controller\ControllerItem'],
                    [8, 'success', 'boolean', false, false,  3, null, 'bool'],
                ],
                'SELECT * FROM field'
            )
        ;
    }
}
