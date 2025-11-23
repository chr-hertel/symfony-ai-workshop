<?php

namespace App\Command;

use App\Conference\ProgramLoader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand('app:conference:schedule', description: 'Load conference schedule from live.symfony.com')]
final readonly class ConferenceScheduleCommand
{
    public function __construct(
        private ProgramLoader $loader,
        #[Autowire('%conference%')]
        private array $conferenceData,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title(\sprintf('Schedule of %s', $this->conferenceData['name']));

        foreach ($this->loader->load() as $event) {
            $io->writeln(\sprintf(
                ' * <info>%s</> <comment>(%s)</>',
                $event->getTitle(),
                $event->getTimeSpan()->toString(),
            ));
        }

        $io->success('Have a great conference!');

        return 0;
    }
}
