<?php declare(strict_types=1);

namespace App\Controller\Controller;

use App\Dto\Php\NodeDto;
use App\Dto\Php\TokenDto;
use App\Request\Controller\UpdateRequest;
use App\Response\SuccessResponse;
use App\Service\Controller\ControllerService;
use App\Service\Php\PhpParser;
use App\Service\Php\PhpPrinter;
use App\Service\String\StrCase;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/api/controller/{uuid}', requirements: ['uuid' => Requirement::UUID_V7], methods: ['PUT'])]
final readonly class UpdateController
{
    public function __construct(
        private PhpParser $phpParser,
        private PhpPrinter $phpPrinter,
        private ControllerService $controllerService,
    ) {}

    public function __invoke(
        Uuid $uuid,
        #[MapRequestPayload] UpdateRequest $request,
    ): SuccessResponse {
        $file = $this->phpParser->parseFile('/tmp/SelfCheckController.php');
        $class = $file->classes[0];
        $tokens = $file->tokens;

        $name = $class->name;
        $newName = $this->controllerService->nameToClassName(StrCase::toPascalCase($request->name));
        $filepath = $file->path;
        if ($name->value !== $newName) {
            unlink($file->path);
            $this->replaceTokens($tokens, $name, $newName);
            $filepath = pathinfo($filepath, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $newName . '.php';
        }

        $route = $class->attribute(Route::class);

        $method = $route->args[1]->value;
        $this->replaceTokens($tokens, $method, "['$request->method']");

        $path = $route->args[0]->value;
        $this->replaceTokens($tokens, $path, "'$request->path'");

        $this->phpPrinter->saveFile($filepath, $tokens);

        return new SuccessResponse();
    }

    public function replaceTokens(array &$tokens, NodeDto $node, string $value): void
    {
        $pos = $node->pos;
        $end = $node->end;

        $newToken = new TokenDto(
            pos: $pos,
            end: $pos,
            value: $value,
            type: '', // todo
        );

        $tokens = array_merge(
            array_slice($tokens, 0, $pos, true),
            [$newToken],
            array_slice($tokens, $end + 1, null, true),
        );
    }
}
