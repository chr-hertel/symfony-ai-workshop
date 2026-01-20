<?php

namespace App\Command;

use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:openai:chat', 'Chat with GPT')]
final readonly class OpenAiChatCommand
{
    public function __construct(
        private PlatformInterface $platform,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $message = $io->ask('What is the message?');

        $messageBag = new MessageBag(
            Message::forSystem('You are a pirate and you write funny.'),
            Message::ofUser($message),
        );

        $response = $this->platform->invoke('gpt-4o-mini', $messageBag);

        $io->block($response->asText());

        return 0;
    }
}
