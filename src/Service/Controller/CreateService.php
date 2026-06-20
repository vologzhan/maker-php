<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Request\Controller\CreateRequest;
use App\Response\Controller\CreateResponse;
use Symfony\Component\Uid\Uuid;

final readonly class CreateService
{
    public function __construct(
        private ControllerHelper $controllerHelper,
    ) {}

    public function __invoke(CreateRequest $request): CreateResponse
    {
        $uuid = Uuid::fromString("019e98a9-592b-7988-8d91-6893b70e38c5"); // todo hardcode

        $className = $this->controllerHelper->nameToClassName($request->name);
        $method = $request->method;
        $path = $request->path;

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
