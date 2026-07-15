<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Dto\Php\NodeDto;
use App\Dto\Php\TokenDto;
use App\Entity\Controller;
use App\Repository\ControllerRepository;
use App\Repository\ResponseRepository;
use App\Request\Controller\UpdateRequest;
use App\Response\Fs\Content\FileContent;
use App\Serializer\FsSerializer;
use App\Service\Filesystem\File\ParsePhpFileService;
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
        private ResponseRepository $responseRepository,
        private ParsePhpFileService $parsePhpFileService,
        private FsSerializer $fsSerializer,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(UpdateRequest $request): FileContent
    {
        $controller = $this->controllerRepository->findById($request->id);
        $response = $request->responseId ? $this->responseRepository->findById($request->responseId) : null;

        $controller
            ->setMethod($request->method)
            ->setPath($request->path)
            ->setResponse($response);

        $tokens = $this->updateFile($controller);

        $this->controllerRepository->save($controller);

        $this->entityManager->flush();

        return $this->fsSerializer->fileContent($controller->getFile(), $tokens);
    }

    public function replaceTokens(array &$tokens, NodeDto $node, string $value): void
    {
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

    /**
     * @return TokenDto[]
     */
    private function updateFile(Controller $controller): array
    {
        // todo не обновляется Response
        $file = $controller->getFile();
        $fileDto = $this->parsePhpFileService->__invoke($file);

        $class = $fileDto->classes[0];
        $tokens = $fileDto->tokens;

        $route = $class->attribute(Route::class);

        $method = $route->args[1]->value;
        $this->replaceTokens($tokens, $method, sprintf("['%s']", $controller->getMethod()));

        $path = $route->args[0]->value;
        $this->replaceTokens($tokens, $path, sprintf("'%s'", $controller->getPath()));

        $this->phpPrinter->saveFile($file->getPath(), $tokens);

        return $tokens;
    }
}
