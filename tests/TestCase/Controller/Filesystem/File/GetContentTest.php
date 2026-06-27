<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Filesystem\File;

use App\Controller\Filesystem\File\GetContentController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetContentController
 */
final class GetContentTest extends ApiTestCase
{
    public function test(): void
    {
        $this->filesystem()->createFile('/tmp/tests/Controller/Filesystem/File/GetContentTest/Controller.php', '<?php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE file RESTART IDENTITY CASCADE;
            INSERT INTO file (id, path, directory_id) VALUES (1, '/tmp/tests/Controller/Filesystem/File/Controller.php', null);
            SQL
        );
        # --------------------------------------------------------------------------------------------------------------

        $this
            ->request('GET', '/api/filesystem/file/1')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "items": [
                    {"pos": 0, "end": 5, "value": "<?php", "type": "T_OPEN_TAG"}
                  ]
                }
                JSON
            );
    }
}
