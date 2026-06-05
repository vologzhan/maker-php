<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\DeleteController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see DeleteController
 */
final class DeleteControllerTest extends ApiTestCase
{
    public function test(): void
    {
        $path = $this->tmpDir() . '/SelfCheckController.php';
        file_put_contents($path, '<?php ');

        $this->assertFileExists($path);

        $this
            ->request('DELETE', '/api/controller/019e98a9-592b-7988-8d91-6893b70e38c5')
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                    "success": true
                }
                JSON
            );

        $this->assertFileDoesNotExist($path);
    }
}
