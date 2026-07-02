<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\GetOneController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetOneController
 */
final class GetOneTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->filesystem()->createFile('/tmp/tests/Controller/Controller/GetOneTest/Controller.php', '<?php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/tests/Controller/Controller/GetOneTest/Controller.php', null);
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/', 'GET', 1, null, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('GET', '/api/controller/1')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "method": "GET",
                  "path": "/",
                  "responseId": null,
                  "content": [
                    {"pos": 0, "end": 5, "value": "<?php", "type": "T_OPEN_TAG"}
                  ]
                }
                JSON
            );
    }
}
