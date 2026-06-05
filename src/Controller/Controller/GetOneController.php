<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Response\Controller\ControllerItemResponse;
use App\Serializer\ControllerSerializer;
use App\Service\Php\PhpParser;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/api/controller/{uuid}', requirements: ['uuid' => Requirement::UUID_V7], methods: ['GET'])]
final readonly class GetOneController
{
    public function __construct(
        private PhpParser $phpParser,
        private ControllerSerializer $controllerSerializer,
    ) {}

    public function __invoke(Uuid $uuid): ControllerItemResponse
    {
        $file = $this->phpParser->parseFile(__DIR__ . '/../SelfCheckController.php'); // todo hardcode
        $class = $file->classes[0];

        return $this->controllerSerializer->controllerItemResponse($uuid, $class);
    }
}
