<?php declare(strict_types=1);

namespace App\Service\Visitor;

use App\Response;
use App\Response\Node\NodeItemInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt as PhpParser;
use PhpParser\NodeVisitorAbstract;

final class TokenizeVisitor extends NodeVisitorAbstract
{
    /** @var NodeItemInterface[] */
    public array $tokens = [];

    public function leaveNode(Node $node) {
        $pos = $node->getStartTokenPos();
        $end = $node->getEndTokenPos();

        $this->tokens[] = match (get_class($node)) {
            PhpParser\Class_::class => new Response\Node\Class_($pos, $end),
            PhpParser\Use_::class => new Response\Node\Use_($pos, $end),
            default => new Response\Node\Node($pos, $end, get_class($node)),
        };
    }
}
