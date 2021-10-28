<?php

declare(strict_types=1);

namespace Core\TriviaGame\Infrastructure\Repository;

use Core\TriviaGame\Domain\QuestionsRepository;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

final class NumbersApiQuestionsRepository implements QuestionsRepository
{
    private const NUMBERS_API_URL = 'http://numbersapi.com/random/trivia?json';

    /**
     * @param ClientInterface $httpClient
     */
    public function __construct(private ClientInterface $httpClient,)
    {}

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getQuestion(): array
    {
        $response = $this->httpClient->send(
            new Request(
                'GET',
                self::NUMBERS_API_URL,
            )
        );

       return json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}

