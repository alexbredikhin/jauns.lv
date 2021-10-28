<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

final class QuestionFormatter
{
    /**
     * @param string $from
     * @param string $to
     * @param string $content
     * @return string
     */
    public static function replaceFirstMatch(string $from, string $to, string $content): string
    {
        $from = '/' . preg_quote($from, '/') . '/';

        return preg_replace($from, $to, $content, 1);
    }
}

