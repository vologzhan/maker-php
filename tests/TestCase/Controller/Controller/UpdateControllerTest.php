<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Controller;

use App\Controller\Controller\UpdateController;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see UpdateController
 */
final class UpdateControllerTest extends ApiTestCase
{
    public function test(): void
    {
        $path = $this->tmpDir() . '/SelfCheckController.php';

        file_put_contents($path, <<<PHP
            <?php declare(strict_types=1);
            
            namespace App\Tests\Fixtures;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class SelfCheckController
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }
            
            PHP
        );

        $this
            ->request('PUT', '/api/controller/019e98a9-592b-7988-8d91-6893b70e38c5', body: <<<JSON
                {
                    "name": "Self check edited",
                    "method": "POST",
                    "path": "/self-check"
                }
                JSON
            )
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                    "success": true
                }
                JSON
            );

        self::assertFileDoesNotExist($path);

        self::assertEquals(<<<PHP
            <?php declare(strict_types=1);

            namespace App\Tests\Fixtures;

            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;

            #[Route('/self-check', methods: ['POST'])]
            final readonly class SelfCheckEditedController
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }

            PHP
            , file_get_contents($this->tmpDir() . '/SelfCheckEditedController.php'),
        );
    }
}
