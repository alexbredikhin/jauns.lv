<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

use DomainException;
use JetBrains\PhpStorm\Pure;
use Throwable;

final class LastQuestionException extends DomainException
{
    #[Pure]
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

