<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('app:openai:test', description: 'Tests if OpenAI API Key looks good and works.')]
final readonly class OpenAiTestCommand
{
    public function __construct(
        #[\SensitiveParameter]
        #[Autowire('%env(OPENAI_API_KEY)%')]
        private ?string $openAiApiKey,
        private HttpClientInterface $httpClient,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Testing the OpenAI API Key');

        if (empty($this->openAiApiKey)) {
            $io->error('No secret OPENAI_API_KEY found.');

            return Command::FAILURE;
        }

        $io->comment('API Key found, testing...');

        if (!str_starts_with($this->openAiApiKey, 'sk-proj-') || 164 !== \strlen($this->openAiApiKey)) {
            $io->error('OpenAI API Key seems to be invalid.');

            return Command::FAILURE;
        }

        $io->comment('API looks valid, calling OpenAI API...');

        try {
            $response = $this->httpClient->request('GET', 'https://api.openai.com/v1/models', [
                'auth_bearer' => $this->openAiApiKey,
            ]);

            if (200 !== $response->getStatusCode()) {
                $io->error(\sprintf('OpenAI API Key seems to be invalid. API returned status code %d.', $response->getStatusCode()));

                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $io->error('Error while calling OpenAI API: '.$e->getMessage());

            return Command::FAILURE;
        }

        $io->comment('API call successful, checking response...');

        $data = $response->toArray();

        if (!isset($data['data']) || !\is_array($data['data'])) {
            $io->error('OpenAI API Key seems to be invalid or not working.');

            return Command::FAILURE;
        }

        $io->comment(\sprintf('OpenAI API returned %d models:', \count($data['data'])));

        $io->listing(array_map(fn (array $model) => $model['id'], $data['data']));

        $io->success('OpenAI API Key is working fine!');

        return Command::SUCCESS;
    }
}
