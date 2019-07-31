<?php

declare(strict_types=1);

namespace App\Service;

use IntlChar;

class NameFormatter
{
    private const SNAKE_GLUE = '_';

    /**
     * Formats the name to camel case.
     *
     * @param string $name       the name to format
     * @param bool   $upperFirst true if you want to use UpperCamelCase instead of lowerCamelCase
     *
     * @return string the formatted name
     */
    public static function toCamelCase(string $name, bool $upperFirst = true): string
    {
        $words = self::split($name);
        $formatted = '';

        foreach ($words as $word) {
            $formatted .= (!$upperFirst && empty($formatted)) ? $word : ucfirst($word);
        }

        return $formatted;
    }

    /**
     * Splits the name to words from camel or snake case.
     *
     * @param string $name the name to split
     *
     * @return string[] the words in the name
     */
    private static function split(string $name): array
    {
        $words = [];
        $word = '';

        foreach (str_split($name) as $character) {
            $isSnaked = $character === self::SNAKE_GLUE;

            if (IntlChar::IsUpper($character) || $isSnaked) {
                $words[] = strtolower($word);
                $word = '';
            }

            if ($isSnaked) {
                continue;
            }

            $word .= $character;
        }

        $words[] = strtolower($word);

        return $words;
    }
}
