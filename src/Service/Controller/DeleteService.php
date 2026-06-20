<?php declare(strict_types=1);

namespace App\Service\Controller;

use App\Service\Php\PhpParser;
use Symfony\Component\Uid\Uuid;

final readonly class DeleteService
{
    public function __construct(
        private PhpParser $phpParser,
    ) {}

    public function __invoke(Uuid $uuid): void
    {
        $file = $this->phpParser->parseFile('/tmp/SelfCheckController.php'); // todo hardcode
        unlink($file->path);
    }
}
