<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

interface TriviaGameRepository
{
    /**
     * @param TriviaGameEntity $questionEntity
     */
    public function save(TriviaGameEntity $questionEntity,): void;


    public function deleteAll(): void;

    /**
     * @return TriviaGameEntity []
     */
    public function getAllQuestions(): array;

    /**
     * @return int
     */
    public function countQuestions(): int;

    /**
     * @return TriviaGameEntity|null
     */
    public function getLastQuestion(): ?TriviaGameEntity;

    /**
     * @param TriviaGameEntity $triviaGameEntity
     */
    public function updateLastQuestion(TriviaGameEntity $triviaGameEntity,): void;
}

