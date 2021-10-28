<?php

declare(strict_types=1);

namespace App\Http\Controllers\TriviaGame;

use Core\TriviaGame\Application\TriviaGameService;
use Core\TriviaGame\Application\Validators\QuestionAnswerValidator;
use Illuminate\Http\JsonResponse;

final class TriviaGameController
{
    /**
     * @param TriviaGameService $triviaGameService
     */
    public function __construct(private TriviaGameService $triviaGameService)
    {}

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function getQuestion(): JsonResponse
    {
        return new JsonResponse($this->triviaGameService->question());
    }

    /**
     * @param QuestionAnswerValidator $request
     * @return JsonResponse
     */
    public function getAnswer(QuestionAnswerValidator $request): JsonResponse
    {
        $checkAnswer = $this->triviaGameService->checkQuestion((int)$request->post('answer'));

        return new JsonResponse($checkAnswer->getMessage(), $checkAnswer->getStatusCode());
    }

    /**
     * @return JsonResponse
     */
    public function cleanGame(): JsonResponse
    {
        $this->triviaGameService->cleanGame();

        return new JsonResponse('Success');
    }
}

