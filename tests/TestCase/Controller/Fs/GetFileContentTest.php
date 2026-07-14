<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Controller\Fs\GetFileContent;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetFileContent
 */
final class GetFileContentTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app/src/Controller')
            ->createFile('/tmp/app/src/Controller/SelfCheck.php', '<?php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;

            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/app/src/Controller/SelfCheck.php', null);
            INSERT INTO project (id, name) VALUES (1, '');
            INSERT INTO controller (id, path, method, project_id, response_id, file_id) VALUES (1, '/api', 'GET', 1, null, 1);
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('GET', '/api/file/1')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "tokens": [
                    {
                        "pos": 0,
                        "end": 5,
                        "value": "<?php",
                        "type": "T_OPEN_TAG"
                    }
                  ],
                  "controller": {
                    "id": 1,
                    "method": "GET",
                    "path": "/api",
                    "responseId": null
                  }
                }
                JSON
            );
    }
}
