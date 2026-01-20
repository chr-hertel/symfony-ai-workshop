<?php

namespace App\Command;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand('app:openai:chat', 'Chat with GPT')]
final readonly class OpenAiChatCommand
{
    public function __construct(
        #[Autowire(service: 'ai.agent.default')]
        private AgentInterface $agent,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $message = $io->ask('What is the message?');

        $messageBag = new MessageBag(
            Message::ofUser($message),
        );

        $response = $this->agent->call($messageBag);

        $io->block($response->getContent());

        return 0;
    }
}
