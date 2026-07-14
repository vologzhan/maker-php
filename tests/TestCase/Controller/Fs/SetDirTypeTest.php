<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SetDirType
 */
final class SetDirTypeTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createFile(
            '/tmp/tests/update_directory_type/SelfCheckController.php',
            <<<PHP
            <?php declare(strict_types=1);
            
            namespace Fixtures\Controller;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class SelfCheckController
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
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO directory (id, path, project_id, parent_id, type) VALUES (1, '', 1, null, null);
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/tests/update_directory_type/SelfCheckController.php', 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('POST', '/api/dir/1/type', body:
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
                  "success": true
                }
                JSON
            );

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'type', 'project_id', 'parent_id'],
                [1, '', 'controller', 1, null],
            ], 'SELECT * FROM directory')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/tests/update_directory_type/SelfCheckController.php', 1],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
                [1, '/', 'GET', 1, null, 1]
            ], 'SELECT * FROM controller');
    }
}
