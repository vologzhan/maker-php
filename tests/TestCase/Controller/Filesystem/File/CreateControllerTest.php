<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Controller\CreateController;
use App\Tests\Infrastructure\ApiTestCase;
use App\Tests\Infrastructure\Attribute\Skip;

/**
 * @see CreateController
 */
#[Skip]
final class CreateControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        unlink('/tmp/tests/Controller/Controller/CreateTest/Controller.php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO directory (id, path, type, project_id, parent_id) VALUES (1, '/tmp/tests/Controller/Controller/CreateTest', 'controller', 1, null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/controller', body:
                <<<JSON
                {
                  "directoryId": 1
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "New controller",
                  "method": "GET",
                  "path": "/",
                  "responseId": 1
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/tests/maker-php/src/Controller/NewController.php',
            <<<PHP
            <?php declare(strict_types=1);

            namespace App\Controller;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class NewController
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }

            PHP
        );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
                [1, '/', 'GET', 1, null, 1],
            ], 'SELECT * FROM controller')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/tests/Controller/Controller/CreateTest/Controller.php', 1],
            ], 'SELECT * FROM file');
    }
}
