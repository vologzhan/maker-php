<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SetDirType
 */
final class SetDirTypeControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app/src/Controller')
            ->createFile('/tmp/app/src/Controller/SelfCheck.php',
                <<<PHP
                <?php declare(strict_types=1);

                namespace Fixture\Controller;
                
                use Fixture\Response\SuccessResponse;
                use Symfony\Component\Routing\Attribute\Route;
                
                #[Route('/api', methods: ['GET'])]
                final readonly class SelfCheck
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
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;

            INSERT INTO directory (id, path, parent_id) VALUES (1, '/tmp/app/src/Controller', null);
            INSERT INTO file (id, path, directory_id) VALUES (default, '/tmp/app/src/Controller/SelfCheck.php', 1);
            INSERT INTO project (id, dir_id) VALUES (1, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/dir/1/type',
                <<<JSON
                {
                  "type": "controller"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 2,
                  "name": "controller.maker"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/app/src/Controller/controller.maker', '');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
                [1, '/api', 'GET', 1, null, 1],
            ], 'SELECT * FROM controller')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/src/Controller/SelfCheck.php', 1],
                [2, '/tmp/app/src/Controller/controller.maker', 1],
            ], 'SELECT * FROM file');
    }
}
