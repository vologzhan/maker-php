<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Dto\Php\NodeDto;
use App\Dto\Php\TokenDto;
use App\Entity\Controller;
use App\Repository\ControllerRepository;
use App\Repository\RequestRepository;
use App\Repository\ResponseRepository;
use App\Request\Controller\UpdateRequest;
use App\Response\Fs\Content\FileContent;
use App\Serializer\FsSerializer;
use App\Service\Filesystem\ParsePhpFileService;
use App\Service\Php\PhpPrinter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route(path: '/api/controller/{id}', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
final readonly class UpdateController
{
    public function __construct(
        private PhpPrinter $phpPrinter,
        private ControllerRepository $controllerRepository,
        private RequestRepository $requestRepository,
        private ResponseRepository $responseRepository,
        private ParsePhpFileService $parsePhpFileService,
        private FsSerializer $fsSerializer,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(UpdateRequest $request): FileContent
    {
        $controller = $this->controllerRepository->findById($request->id);
        $req = $this->requestRepository->findByIdOrNull($request->requestId);
        $response = $this->responseRepository->findByIdOrNull($request->responseId);

        $controller
            ->setMethod($request->method)
            ->setPath($request->path)
            ->setRequest($req)
            ->setResponse($response);

        $tokens = $this->updateFile($controller);

        $this->controllerRepository->save($controller);

        $this->entityManager->flush();

        return $this->fsSerializer->fileContent($controller->getFile(), $tokens);
    }

    /**
     * @return TokenDto[]
     */
    private function updateFile(Controller $controller): array
    {
        $file = $controller->getFile();
        $fileDto = $this->parsePhpFileService->__invoke($file);

        $class = $fileDto->classes[0];
        $tokens = $fileDto->tokens;

        // todo обновить импорт в use
        $responseFullClassName = $controller->getResponse()->getClassName();
        $parts = explode('\\', $responseFullClassName);
        $responseClassName = array_pop($parts);
        $return = $class->method('__invoke')->return;
        $this->replaceTokens($tokens, $return, $responseClassName);

        $route = $class->attribute(Route::class);

        $method = $route->args[1]->value;
        $this->replaceTokens($tokens, $method, sprintf("['%s']", $controller->getMethod()));

        $path = $route->args[0]->value;
        $this->replaceTokens($tokens, $path, sprintf("'%s'", $controller->getPath()));

        $this->phpPrinter->saveFile($file->getPath(), $tokens);

        return $tokens;
    }

    private function replaceTokens(array &$tokens, NodeDto $node, string $value): void
    {
        // todo у токенов появляется смещение и поэтому работает обновление только снизу вверх
        $pos = $node->pos;
        $end = $node->end;

        $newToken = new TokenDto(
            pos: $pos,
            end: $pos,
            value: $value,
            type: '', // todo нужно ли это вообще обновлять при редактировании? Состояние хранится в БД
        );

        $tokens = array_merge(
            array_slice($tokens, 0, $pos, true),
            [$newToken],
            array_slice($tokens, $end + 1, null, true),
        );
    }
}
