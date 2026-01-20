<?php

namespace App\Conference\Data;

class Talk extends Event
{
    public function __construct(
        string $title,
        private readonly string $speaker,
        private readonly string $description,
        TimeSpan $timeSpan,
        private readonly Track $track,
        Slot $slot,
    ) {
        parent::__construct($title, $timeSpan, $slot);
    }

    public function getSpeaker(): string
    {
        return $this->speaker;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTrack(): string
    {
        return $this->track->value;
    }

    public function isOver(\DateTimeImmutable $now): bool
    {
        return $now > $this->getTimeSpan()->getEnd();
    }
}
