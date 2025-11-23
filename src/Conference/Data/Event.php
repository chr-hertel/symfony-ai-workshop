<?php

namespace App\Conference\Data;

class Event
{
    public function __construct(
        private readonly string $title,
        private readonly TimeSpan $timeSpan,
        private readonly Slot $slot,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTimeSpan(): TimeSpan
    {
        return $this->timeSpan;
    }

    public function getSlot(): Slot
    {
        return $this->slot;
    }
}
