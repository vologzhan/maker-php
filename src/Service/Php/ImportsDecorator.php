<?php declare(strict_types=1);

namespace App\Service\Php;

use App\Dto\Php\ImportDto;
use App\Dto\Php\NodeDto;

final class ImportsDecorator
{
    /** @var array<string, ImportDto> */
    private array $imports = [];

    /**
     * @param ImportDto[] $imports
     */
    public function __construct(array $imports)
    {
        foreach ($imports as $import) {
            $this->imports[$import->name->value] = $import;
        }
    }

    public function create(string $name, string $alias = ''): void
    {
        if (!isset($this->imports[$name])) {
            $this->imports[$name] = new ImportDto(
                name: new NodeDto(
                    pos: 0, // todo
                    end: 0, // todo
                    value: $name,
                ),
                alias: $alias ? new NodeDto(
                    pos: 0, // todo
                    end: 0, // todo
                    value: $alias,
                ): null,
            );
        }
    }

    public function remove(string $name): void
    {
        unset($this->imports[$name]);
    }

    public function toString(): string
    {
        ksort($this->imports);

        $out = [];
        foreach ($this->imports as $import) {
            $str = sprintf('use %s', $import->name->value);
            if ($import->alias) {
                $str = sprintf('%s as %s', $str, $import->alias->value);
            }
            $str = sprintf('%s;', $str);
            $out[] = $str;
        }

        return implode("\n", $out);
    }
}
