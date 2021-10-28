<?php

declare(strict_types=1);

namespace Core\TriviaGame\Application\Providers;

use Core\TriviaGame\Domain\QuestionsRepository;
use Core\TriviaGame\Domain\TriviaGameRepository;
use Core\TriviaGame\Infrastructure\Repository\NumbersApiQuestionsRepository;
use Core\TriviaGame\Infrastructure\Repository\TriviaGameSessionRepository;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

final class TriviaGameProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(QuestionsRepository::class, static function() {
            return new NumbersApiQuestionsRepository(new Client());
        });

        $this->app->bind(TriviaGameRepository::class, TriviaGameSessionRepository::class);
    }
}

