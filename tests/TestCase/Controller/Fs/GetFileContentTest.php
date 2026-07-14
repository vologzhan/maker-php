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
            ->deleteDir('/tmp/app')
            ->createFile('/tmp/app/index.php', '<?php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;

            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/app/index.php', null);
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
                  ]
                }
                JSON
            );
    }
}
