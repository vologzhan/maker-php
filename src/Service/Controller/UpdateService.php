<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Dto\Php\NodeDto;
use App\Dto\Php\TokenDto;
use App\Request\Controller\UpdateRequest;
use App\Service\Php\PhpParser;
use App\Service\Php\PhpPrinter;
use App\Service\String\StrCase;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateService
{
    public function __construct(
        private PhpParser $phpParser,
        private PhpPrinter $phpPrinter,
        private ControllerHelper $controllerHelper,
    ) {}

    public function __invoke(Uuid $uuid, UpdateRequest $request): void
    {
        $file = $this->phpParser->parseFile('/tmp/SelfCheckController.php');
        $class = $file->classes[0];
        $tokens = $file->tokens;

        $name = $class->name;
        $newName = $this->controllerHelper->nameToClassName(StrCase::toPascalCase($request->name));
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
}
