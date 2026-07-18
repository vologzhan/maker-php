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
            ->createFile('/tmp/app/project.maker')
            ->createFile('/tmp/app/Controller/controller.maker')
            ->createFile('/tmp/app/Controller/SelfCheck.php',
                <<<'PHP'
                <?php declare(strict_types=1);

                namespace Fixture\Controller;
                
                use Fixture\Response\SuccessResponse;
                use Fixture\Request\EmptyRequest;
                use Symfony\Component\Routing\Attribute\Route;
                
                #[Route('/api', methods: ['GET'])]
                final readonly class SelfCheck
                {
                    public function __invoke(EmptyRequest $request): SuccessResponse
                    {
                        return new SuccessResponse();
                    }
                }

                PHP
            )
            ->createFile('/tmp/app/Request/request.maker')
            ->createFile('/tmp/app/Request/EmptyRequest.php',
                <<<'PHP'
                <?php declare(strict_types=1);
                
                namespace Fixture\Request;
                
                final readonly class EmptyRequest
                {
                }

                PHP
            )
            ->createFile('/tmp/app/Response/response.maker')
            ->createFile('/tmp/app/Response/SuccessResponse.php',
                <<<'PHP'
                <?php declare(strict_types=1);
                
                namespace Fixture\Response;
                
                final readonly class SuccessResponse
                {
                    public function __construct(
                        public bool $success = true,
                    ) {}
                }

                PHP
            )
        ;

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
                      "name": "Controller",
                      "dirs": [],
                      "files": [
                        {
                          "id": 1,
                          "name": "SelfCheck.php"
                        },
                        {
                          "id": 2,
                          "name": "controller.maker"
                        }
                      ]
                    },
                    {
                      "id": 3,
                      "name": "Request",
                      "dirs": [],
                      "files": [
                        {
                          "id": 3,
                          "name": "EmptyRequest.php"
                        },
                        {
                          "id": 4,
                          "name": "request.maker"
                        }
                      ]
                    },
                    {
                      "id": 4,
                      "name": "Response",
                      "dirs": [],
                      "files": [
                        {
                          "id": 5,
                          "name": "SuccessResponse.php"
                        },
                        {
                          "id": 6,
                          "name": "response.maker"
                        }
                      ]
                    }
                  ],
                  "files": [
                    {
                      "id": 7,
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
                [2, '/tmp/app/Controller', 1],
                [3, '/tmp/app/Request', 1],
                [4, '/tmp/app/Response', 1],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/Controller/SelfCheck.php', 2],
                [2, '/tmp/app/Controller/controller.maker', 2],
                [3, '/tmp/app/Request/EmptyRequest.php', 3],
                [4, '/tmp/app/Request/request.maker', 3],
                [5, '/tmp/app/Response/SuccessResponse.php', 4],
                [6, '/tmp/app/Response/response.maker', 4],
                [7, '/tmp/app/project.maker', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'dir_id'],
                [1, 1],
            ], 'SELECT * FROM project')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id', 'request_id'],
                [1, '/api', 'GET', 1, 1, 1, 1]
            ], 'SELECT * FROM controller')
            ->assertEquals([
                ['id', 'class_name', 'project_id', 'file_id'],
                [1, 'Fixture\Request\EmptyRequest', 1, 3]
            ], 'SELECT * FROM request')
            ->assertEquals([
                ['id', 'class_name', 'project_id', 'file_id'],
                [1, 'Fixture\Response\SuccessResponse', 1, 5]
            ], 'SELECT * FROM response')
        ;
    }
}
