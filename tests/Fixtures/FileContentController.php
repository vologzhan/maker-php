<?php declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Request\FileContentRequest;
use App\Response\FileContentResponse;
use App\Service\Visitor\TokenizeVisitor;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class FileContentController
{
    #[Route('/api/file/content', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString] FileContentRequest $request,
    ): FileContentResponse {
        $content = file_get_contents($request->path);

        $parser = new ParserFactory()->createForNewestSupportedVersion();
        $stmts = $parser->parse($content);
        $tokens = $parser->getTokens();

        $tokenizer = new TokenizeVisitor();
        $traverser = new NodeTraverser(
            new CloningVisitor(), // Run CloningVisitor before making changes to the AST.
            new NameResolver(options: [
                'preserveOriginalNames' => false, // default: false
                'replaceNodes' => false, // default: true
            ]),
            $tokenizer,
        );
        $newStmts = $traverser->traverse($stmts);

        # ----------------------------------------------------------------------------------------------------------
//        $printer = new Standard;
//        $newCode = $printer->printFormatPreserving($newStmts, $stmts, $tokens);

        return new FileContentResponse($content, $tokenizer->tokens);
    }
}
