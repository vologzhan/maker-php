<?php declare(strict_types=1);

namespace App\Request\Controller;

final readonly class CreateRequest
{
    public string $name;
    public string $path;
    public string $method;
}
