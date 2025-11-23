<?php

namespace App\Conference\Data;

final class Slot
{
    /**
     * @param Event[] $events
     */
    public function __construct(
        private readonly TimeSpan $timeSpan,
        private readonly ?self $previous = null,
        private ?self $next = null,
        private array $events = [],
    ) {
    }

    public function getTimeSpan(): TimeSpan
    {
        return $this->timeSpan;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    public function isFirst(): bool
    {
        return null === $this->previous;
    }

    public function getPrevious(): self
    {
        if (null === $this->previous) {
            throw new \DomainException('Cannot fetch previous slot of first slot.');
        }

        return $this->previous;
    }

    public function isLast(): bool
    {
        return null === $this->next;
    }

    public function getNext(): self
    {
        if (null === $this->next) {
            throw new \DomainException('Cannot fetch next slot of last slot.');
        }

        return $this->next;
    }

    public function setNext(self $next): void
    {
        $this->next = $next;
    }
}
