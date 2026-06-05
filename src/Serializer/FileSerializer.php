<?php declare(strict_types=1);

namespace App\Serializer;

use App\Dto\Php\TokenDto;
use App\Response\File\FileResponse;
use App\Response\File\TokenItem;

final readonly class FileSerializer
{
    /**
     * @param TokenDto[] $tokens
     */
    public function fileResponse(array $tokens): FileResponse
    {
        return new FileResponse(
            tokens: array_map(static fn(TokenDto $token) => new TokenItem(
                pos: $token->pos,
                end: $token->end,
                value: $token->value,
                type: $token->type,
            ), $tokens),
        );
    }
}
