<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs\CreateFile;

use App\Controller\Fs\CreateFile;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see CreateFile
 */
final class CreateFileControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app')
            ->createDir('/tmp/app');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;

            INSERT INTO directory (id, path, parent_id) VALUES (1, '/tmp/app', null);
            INSERT INTO project (id, dir_id) VALUES (1, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/file',
                <<<JSON
                {
                  "directoryId": 1,
                  "name": "GetOne",
                  "type": "controller"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "GetOne.php"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/app/GetOne.php',
            <<<PHP
            <?php declare(strict_types=1);
            
            namespace App\Controller;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class GetOne
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
                [1, '/tmp/app/GetOne.php', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
                [1, '/', 'GET', 1, null, 1],
            ], 'SELECT * FROM controller');
    }
}
