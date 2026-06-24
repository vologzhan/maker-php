<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\CreateController;
use App\Tests\Infrastructure\Annotation\Skip;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateController
 */
final class CreateControllerTest extends ApiTestCase
{
//    protected function setUp(): void
//    {
//        unlink('/tmp/tests/maker-php/src/Controller/NewController.php');
//
//        $this->connectionPsql()->execute(<<<SQL
//            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
//
//            BEGIN;
//            INSERT INTO project (id, name, path) VALUES (1, 'maker-php', '/tmp/tests/maker-php');
//
//            INSERT INTO directory (id, project_id, path)
//            VALUES (1, 1, 'src/Controller'),
//                   (2, 1, 'src/Response');
//
//            INSERT INTO response (id, name, filepath, project_id, class_name)
//            VALUES (1, 'SuccessResponse', 'src/Response/SuccessResponse.php', 1, 'App\Response\SuccessResponse');
//
//            COMMIT;
//            SQL
//        );
//    }

    #[Skip]
    public function test(): void
    {
        $this
            ->request('POST', '/api/controller', body: <<<JSON
                {
                  "directoryId": 1
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "id": 1,
                  "name": "New controller",
                  "method": "GET",
                  "path": "/",
                  "responseId": 1
                }
                JSON
            );

        self::assertFileContentEquals(<<<PHP
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

            PHP, '/tmp/tests/maker-php/src/Controller/NewController.php');

        $this->connectionPsql()
            ->assertEquals([
                ['id', 'name', 'path', 'method', 'filepath', 'project_id', 'response_id'],
                [1, 'New controller', '/', 'GET', '/tmp/tests/maker-php/src/Controller/NewController.php', 1, 1],
            ], 'SELECT * FROM controller');
    }
}
