<?php declare(strict_types=1);

namespace App\Service\Php;

use App\Dto\Php\TokenDto;

final readonly class PhpPrinter
{
    /**
     * @param TokenDto[] $tokens
     */
    public function saveFile(string $path, array $tokens): void
    {
        $content = [];
        foreach ($tokens as $token) {
            $content[] = $token->value;
        }

        file_put_contents($path, implode($content));
    }
}
