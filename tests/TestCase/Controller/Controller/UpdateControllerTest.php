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
            
            namespace Fixture\Controller;
            
            use Fixture\Response\SuccessResponse;
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
            INSERT INTO file (id, path, directory_id) VALUES
                (1, '/tmp/app/Controller.php', null),
                (2, '/tmp/app/EmptyRequest.php', null),
                (3, '/tmp/app/SuccessResponse.php', null),
                (4, '/tmp/app/OtherResponse.php', null);
            INSERT INTO project (id, dir_id) VALUES (1, null);
            INSERT INTO request (id, class_name, project_id, file_id) VALUES (1, 'Fixture\Response\EmptyRequest', 1, 2);
            INSERT INTO response (id, class_name, project_id, file_id) VALUES
                (1, 'Fixture\Response\SuccessResponse', 1, 3),
                (2, 'Fixture\Response\OtherResponse', 1, 4);
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, 1, 1);
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
                    "requestId": 1,
                    "responseId": 2
                }
                JSON
            )
            ->expectedCode(200); // todo expected content tokens

        $this->filesystem()->assertFileContentEquals('/tmp/app/Controller.php',
            <<<'PHP'
            <?php declare(strict_types=1);

            namespace Fixture\Controller;

            use Fixture\Request\EmptyRequest;
            use Fixture\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;

            #[Route('/self-check', methods: ['POST'])]
            final readonly class Controller
            {
                public function __invoke(EmptyRequest $request): OtherResponse
                {
                    return new SuccessResponse();
                }
            }
            PHP
        );

        $this->connectionPsql()->assertEquals([
            ['id', 'path', 'method', 'project_id', 'response_id', 'file_id', 'request_id'],
            [1, '/self-check', 'POST', 1, 2, 1, 1]
        ], 'SELECT * FROM controller');
    }
}
