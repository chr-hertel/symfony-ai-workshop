<?php

namespace App\Conference;

use App\Conference\Data\Event;
use App\Conference\Scraper\Client;
use App\Conference\Scraper\Parser;

final readonly class ProgramLoader
{
    public function __construct(
        private Client $client,
        private Parser $parser,
    ) {
    }

    /**
     * @return iterable<Event>
     */
    public function load(): iterable
    {
        $schedule = $this->client->getSchedule();

        foreach ($this->parser->extractSlots($schedule) as $slot) {
            yield from $slot->getEvents();
        }
    }
}
