<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Skip
{
}
