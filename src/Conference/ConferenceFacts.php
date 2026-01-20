<?php

namespace App\Conference;

use Symfony\AI\Agent\Input;
use Symfony\AI\Agent\Memory\Memory;
use Symfony\AI\Agent\Memory\MemoryProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class ConferenceFacts implements MemoryProviderInterface
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/fixtures/conference.md')]
        private string $conferenceFacts,
    ) {
    }

    public function load(Input $input): array
    {
        $content = file_get_contents($this->conferenceFacts);

        if (false === $content) {
            return [];
        }

        return [
            new Memory($content),
        ];
    }
}
