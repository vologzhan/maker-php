<?php declare(strict_types=1);

namespace App\Service\Php;

use App\Dto\Php\FileDto;
use App\Service\Php\Visitor\CollectorVisitor;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

final readonly class PhpParser
{
    public function parseFile(string $path): FileDto
    {
        $content = file_get_contents($path);
        $collector = $this->parseContent($content);

        return new FileDto(
            path: $path,
            classes: $collector->classes,
            tokens: $collector->tokens,
            uses: $collector->uses,
        );
    }

    public function parseContent(string $content): CollectorVisitor
    {
        # --------------------------------------------------------------------------------------------------------------
        $parser = new ParserFactory()->createForNewestSupportedVersion();
        $stmts = $parser->parse($content);

        $tokens = $parser->getTokens();
        $tokens = array_slice($tokens, 0, -1); // delete EOF

        # --------------------------------------------------------------------------------------------------------------
        $nameResolver = new NameResolver(options: [
            'preserveOriginalNames' => false, // default: false
            'replaceNodes' => false, // default: true
        ]);

        $collector = new CollectorVisitor($tokens, $nameResolver->getNameContext());

        $traverser = new NodeTraverser(
            new CloningVisitor(), // Run CloningVisitor before making changes to the AST.
            $nameResolver,
            $collector,
        );

        $newStmts = $traverser->traverse($stmts);
        # --------------------------------------------------------------------------------------------------------------
//        $printer = new Standard;
//        $newCode = $printer->printFormatPreserving($newStmts, $stmts, $parserTokens);

        return $collector;
    }
}
