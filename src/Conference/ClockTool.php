<?php

namespace App\Conference;

use Symfony\AI\Agent\Toolbox\Attribute\AsTool;
use Symfony\Component\Clock\ClockInterface;

#[AsTool('clock', 'Get the current date and time in Europe/Berlin timezone')]
final readonly class ClockTool
{
    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    /**
     * @param string $timeZone The timezone identifier, e.g. "Europe/Berlin"
     */
    public function __invoke(string $timeZone): string
    {
        return $this->clock
            ->now()
            ->setTimezone(new \DateTimeZone($timeZone))
            ->format('Y-m-d H:i:s');
    }
}
