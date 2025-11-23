<?php

namespace App\Conference\Scraper;

use App\Conference\Data\Event;
use App\Conference\Data\Slot;
use App\Conference\Data\Talk;
use App\Conference\Data\TimeSpan;
use App\Conference\Data\Track;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DomCrawler\Crawler;

final class Parser
{
    private const array TRACKS = [
        '0' => Track::Symfony,
        '1' => Track::SensioLabs,
        '2' => Track::UpsunSmile,
    ];

    public function __construct(private readonly LoggerInterface $logger = new NullLogger())
    {
    }

    /**
     * @return Slot[]
     */
    public function extractSlots(string $response): array
    {
        $crawler = new Crawler($response);
        $slots = [];
        /** @var ?Slot $prevSlot */
        $prevSlot = null;

        // Extract slots
        $crawler->filter('.schedule-row')->each(function (Crawler $row) use ($crawler, &$slots, &$prevSlot) {
            $startsAt = $row->filter('.schedule-time')->attr('data-starts-at');
            $endsAt = $row->filter('.schedule-time')->attr('data-ends-at');

            if (null === $startsAt || null === $endsAt) {
                $this->logger->warning('Cannot collect start or end time for slot');

                return;
            }

            $start = new \DateTimeImmutable($startsAt);
            $end = new \DateTimeImmutable($endsAt);

            $timeSpan = new TimeSpan($start, $end);
            $slot = new Slot($timeSpan, $prevSlot);
            $prevSlot?->setNext($slot);

            // Extract events
            $row->filter('.schedule-event')->each(function (Crawler $event) use ($slot, $timeSpan) {
                $title = $event->filter('.schedule-event-title')->text();

                $slot->addEvent(new Event($title, $timeSpan, $slot));
            });

            // Extract talks
            $row->filter('.schedule-talk')->each(function (Crawler $talk, int $index) use ($crawler, $slot, $timeSpan) {
                $title = $talk->filter('.schedule-talk-title')->text();
                $id = $talk->filter('.schedule-talk-title')->attr('href');

                $slot->addEvent(new Talk(
                    $title,
                    $talk->filter('.schedule-talk-author')->text(),
                    null !== $id ? $crawler->filter('.schedule-list '.$id.' .editable-content')->text() : '',
                    $timeSpan,
                    '1' === $talk->attr('colspan') ? self::TRACKS[$index] : Track::Symfony,
                    $slot,
                ));
            });

            $slots[] = $slot;
            $prevSlot = $slot;
        });

        return $slots;
    }
}
