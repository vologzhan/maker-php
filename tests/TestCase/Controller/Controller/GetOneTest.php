<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\GetOneController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetOneController
 */
final class GetOneTest extends ApiTestCase
{
    public function test(): void
    {
        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/maker-test/filesystem/SelfChekController.php', null);
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, null, 1);
            SQL
        );
        # --------------------------------------------------------------------------------------------------------------

        $this
            ->request('GET', '/api/controller/1')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "method": "GET",
                  "path": "/",
                  "responseId": null
                }
                JSON
            );
    }
}
