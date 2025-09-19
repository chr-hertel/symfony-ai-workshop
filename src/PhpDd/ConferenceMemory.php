<?php

declare(strict_types=1);

namespace App\PhpDd;

use Symfony\AI\Agent\Input;
use Symfony\AI\Agent\Memory\Memory;
use Symfony\AI\Agent\Memory\MemoryProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class ConferenceMemory implements MemoryProviderInterface
{

    public function __construct(
        #[Autowire('%kernel.project_dir%/php-dev-day-2025.md')]
        private string $filePath,
    ) {
    }

    public function load(Input $input): array
    {
        $content = file_get_contents($this->filePath);

        if (false === $content) {
            return [];
        }

        return [
            new Memory($content),
        ];
    }
}
