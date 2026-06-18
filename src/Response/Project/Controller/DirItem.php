<?php declare(strict_types=1);

namespace App\Response\Project\Controller;

use App\Service\Controller\ControllerService;

final class DirItem
{
    public function __construct(
        public readonly string $name = ControllerService::DIR_NAME,
        /** @var DirItem[] */
        public array $directories = [],
        /** @var ControllerItem[] */
        public array $files = [],
    ) {}

    public function getOrCreateDir(string $name): DirItem
    {
        foreach ($this->directories as $dir) {
            if ($dir->name === $name) {
                return $dir;
            }
        }

        $dir = new DirItem(name: $name);
        $this->directories[] = $dir;

        return $dir;
    }
}
