<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Filesystem\File\CreateController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateController
 */
final class CreateControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        unlink('/tmp/tests/Controller/Filesystem/File/CreateControllerTest/NewController.php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO directory (id, path, type, project_id, parent_id) VALUES (1, '/tmp/tests/Controller/Filesystem/File/CreateControllerTest', null, 1, null);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/filesystem/file', body: <<<JSON
                {
                  "directoryId": 1,
                  "name": "New",
                  "type": "controller"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "NewController.php",
                  "type": "controller"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/tests/Controller/Filesystem/File/CreateControllerTest/NewController.php',
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
                ['id', 'path', 'directory_id'],
                [1, '/tmp/tests/Controller/Filesystem/File/CreateControllerTest/NewController.php', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
                [1, '/', 'GET', 1, null, 1],
            ], 'SELECT * FROM controller');
    }
}
