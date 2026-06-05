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
        $tmpDir = $this->tmpDir();

        file_put_contents($tmpDir . '/SelfCheckControllerTest.php', <<<PHP
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

        $actualContent = file_get_contents($this->tmpDir() . '/SelfCheckControllerTest.php'); // todo rename file

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

            PHP, $actualContent);
    }
}
