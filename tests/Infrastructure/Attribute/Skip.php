<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
final readonly class Skip
{
}
