<?php declare(strict_types=1);

namespace App\Request\Controller;

final class CreateRequest
{
    public string $name;
    public string $method;
    public string $path;
}
