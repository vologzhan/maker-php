<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\DeleteController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see DeleteController
 */
final class DeleteTest extends ApiTestCase
{
    public function test(): void
    {
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/tests/Controller/Controller/DeleteTest/Controller.php', null);
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '', '', 1, null, 1);
            SQL
        );

        $this->filesystem()->createFile('/tmp/tests/Controller/Controller/DeleteTest/Controller.php', '<?php');

        # --------------------------------------------------------------------------------------------------------------

        $this
            ->request('DELETE', '/api/controller/1')
            ->expectedCode(200)
            ->expectedJsonContent('{"success":true}');

        self::assertFileDoesNotExist('/tmp/tests/Controller/Controller/DeleteTest/Controller.php');

        $this
            ->connectionPsql()
            ->assertEquals([
                ['id', 'path', 'directory_id'],
            ], 'SELECT * FROM file')
            ->assertEquals([
                ['id', 'path', 'method', 'project_id', 'response_id', 'file_id'],
            ], 'SELECT * FROM controller');
    }
}
