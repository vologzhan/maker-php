<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs\SetDirType;

use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SetDirType
 */
final class SetDirTypeResponseTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app/src/Response')
            ->createFile('/tmp/app/src/Response/SuccessResponse.php',
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
            );

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;

            INSERT INTO directory (id, path, parent_id) VALUES (1, '/tmp/app/src/Response', null);
            INSERT INTO file (id, path, directory_id) VALUES (default, '/tmp/app/src/Response/SuccessResponse.php', 1);
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
                  "type": "response"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 2,
                  "name": "response.maker"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/app/src/Response/response.maker', '');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'class_name', 'project_id', 'file_id'],
                [1, 'Fixture\Response\SuccessResponse', 1, 1],
            ], 'SELECT * FROM response')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/src/Response/SuccessResponse.php', 1],
                [2, '/tmp/app/src/Response/response.maker', 1],
            ], 'SELECT * FROM file');
    }
}
