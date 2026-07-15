<?php declare(strict_types=1);

namespace App\Service\Fs;

use App\Entity\Directory;

final readonly class DirHelper
{
    public function getProjectDir(Directory $dir): Directory
    {
        for (; $dir !== null; $dir = $dir->getParent()) {
            if ($dir->getProject() !== null) {
                return $dir;
            }
        }

        throw new \Exception('Project not found');
    }
}
