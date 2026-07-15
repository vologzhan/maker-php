<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs\SetDirType;

use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see SetDirType
 */
final class SetDirTypeRequestTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app/src/Request')
            ->createFile('/tmp/app/src/Request/GetOneRequest.php',
                <<<'PHP'
                <?php declare(strict_types=1);
                
                namespace Fixture\Request;
                
                final readonly class GetOneRequest
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

            INSERT INTO directory (id, path, parent_id) VALUES (1, '/tmp/app/src/Request', null);
            INSERT INTO file (id, path, directory_id) VALUES (default, '/tmp/app/src/Request/GetOneRequest.php', 1);
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
                  "type": "request"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 2,
                  "name": "request.maker"
                }
                JSON
            );

        $this->filesystem()->assertFileContentEquals('/tmp/app/src/Request/request.maker', '');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'class_name', 'project_id', 'file_id'],
                [1, 'Fixture\Request\GetOneRequest', 1, 1],
            ], 'SELECT * FROM request')
            ->assertEquals([
                ['id', 'path', 'directory_id'],
                [1, '/tmp/app/src/Request/GetOneRequest.php', 1],
                [2, '/tmp/app/src/Request/request.maker', 1],
            ], 'SELECT * FROM file');
    }
}
