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
        $this->filesystem()->createFile('/tmp/tests/filesystem/SelfChekController.php', '<?php');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php');

            INSERT INTO directory (id, path, project_id, parent_id, type)
            VALUES (1, '/tmp/maker-test/filesystem', 1, null, 'project');
            
            INSERT INTO file (id, path, directory_id)
            VALUES (1, '/tmp/maker-test/filesystem/SelfChekController.php', 1);
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
                    {"pos": 0, "end": 6, "value": "<?php ", "type": "T_OPEN_TAG"},
                    {"pos": 6, "end": 13, "value": "declare", "type": "T_DECLARE"},
                    {"pos": 13, "end": 14, "value": "(", "type": "("},
                    {"pos": 14, "end": 26, "value": "strict_types", "type": "T_STRING"},
                    {"pos": 26, "end": 27, "value": "=", "type": "="},
                    {"pos": 27, "end": 28, "value": "1", "type": "T_LNUMBER"},
                    {"pos": 28, "end": 29, "value": ")", "type": ")"},
                    {"pos": 29, "end": 30, "value": ";", "type": ";"}
                  ]
                }
                JSON
            );
    }
}
