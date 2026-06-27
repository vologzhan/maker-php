<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Entity\Controller;
use App\Entity\File;
use App\Entity\Project;
use App\Repository\ControllerRepository;
use App\Service\Php\PhpParser;
use Symfony\Component\Routing\Attribute\Route;

final readonly class IndexController
{
    public function __construct(
        private PhpParser $phpParser,
        private ControllerRepository $controllerRepository,
    ) {}

    public function __invoke(Project $project, File $file): void
    {
        $fileDto = $this->phpParser->parseFile($file->getPath());
        $class = $fileDto->classes[0];
        $routeAttribute = $class->attribute(Route::class);

        $methodInvoke = $class->method('__invoke');
        $responseClassName = $methodInvoke->return->value;
        $responseMap = $project->getResponsesMap();
        $response = $responseMap[$responseClassName] ?? null;

        $methods = $routeAttribute->oneOrNullArgument('methods');
        $firstMethod = $methods?->value?->value[0] ?? null;

        $controller = new Controller()
            ->setPath($routeAttribute->args[0]->value->value)
            ->setMethod($firstMethod)
            ->setFile($file)
            ->setResponse($response);

        $project->addController($controller);

        $this->controllerRepository->save($controller);
    }
}
