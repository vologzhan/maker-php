<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\UpdateController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see UpdateController
 */
final class UpdateControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createFile('/tmp/app/Controller.php',
            <<<PHP
            <?php declare(strict_types=1);
            
            namespace Fixtures\Controller;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class Controller
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }
            PHP
        );

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, dir_id) VALUES (1, null);
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/app/Controller.php', null);
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, null, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('PUT', '/api/controller/1', body:
                <<<JSON
                {
                    "method": "POST",
                    "path": "/self-check",
                    "responseId": null
                }
                JSON
            )
            ->expectedCode(200); // todo expected content tokens

        $this->filesystem()->assertFileContentEquals('/tmp/tests/Controller/Controller/UpdateControllerTest/Controller.php',
            <<<PHP
            <?php declare(strict_types=1);

            namespace Fixtures\Controller;

            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;

            #[Route('/self-check', methods: ['POST'])]
            final readonly class Controller
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }
            PHP
        );

        $this->connectionPsql()->assertEquals([
            ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
            [1, '/self-check', 'POST', 1, null, 1]
        ], 'SELECT * FROM controller');
    }
}
