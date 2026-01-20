<?php

namespace App\Conference\Scraper;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class Client
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%conference%')]
        private array $conferenceData,
    ) {
    }

    public function getSchedule(): string
    {
        return $this->httpClient
            ->request('GET', $this->conferenceData['url'])
            ->getContent();
    }
}
