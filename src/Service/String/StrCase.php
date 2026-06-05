<?php declare(strict_types=1);

namespace App\Service\String;

use Symfony\Component\String\UnicodeString;

final readonly class StrCase
{
    public static function toSentence(string $str): string
    {
        if ($str === '') {
            return '';
        }
        $snakeCase = self::toSnakeCase($str);
        $words = explode('_', $snakeCase);
        $words[0] = ucfirst($words[0]);

        return implode(' ', $words);
    }

    public static function toSnakeCase(string $str): string
    {
        return new UnicodeString($str)->snake()->toString();
    }

    public static function toPascalCase(string $str): string
    {
        return new UnicodeString($str)->pascal()->toString();
    }
}
