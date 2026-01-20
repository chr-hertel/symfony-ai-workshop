<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('app:openai:chat', 'Chat with GPT')]
final readonly class OpenAiChatCommand
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[\SensitiveParameter]
        #[Autowire('%env(OPENAI_API_KEY)%')]
        private string $apiKey,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $message = $io->ask('What is the message?');

        $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => $this->apiKey,
            'json' => [
                'model' => 'gpt-4o',
                'temperature' => 1.0,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a pirate and you write funny.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ],
        ]);


        $assistantMessage = $response->toArray()['choices'][0]['message']['content'];

        $io->block($assistantMessage);

        return 0;
    }
}
