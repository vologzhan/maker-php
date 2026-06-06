<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Request\Controller\CreateRequest;
use App\Response\Controller\CreateResponse;
use App\Service\Controller\ControllerService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/controller', methods: ['POST'])]
final readonly class CreateController
{
    public function __construct(
        private ControllerService $controllerService,
    ) {}

    public function __invoke(
        #[MapRequestPayload] CreateRequest $request,
    ): CreateResponse {
        $uuid = Uuid::fromString("019e98a9-592b-7988-8d91-6893b70e38c5"); // todo hardcode

        $className = $this->controllerService->nameToClassName($request->name);
        $method = ''; // todo from request
        $path = ''; // todo from request

        $content = <<<PHP
            <?php declare(strict_types=1);

            namespace App\Controller;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('$path', methods: ['$method'])]
            final readonly class $className
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }

            PHP;

        file_put_contents("/tmp/$className.php", $content); // todo hardcode

        return new CreateResponse($uuid);
    }
}
