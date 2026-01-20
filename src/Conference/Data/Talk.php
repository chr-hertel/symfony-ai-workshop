<?php

namespace App\Conference\Data;

use Symfony\AI\Store\Document\Metadata;

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

    public function getContent(): string
    {
        return <<<CONTENT
            Title: {$this->getTitle()}
            Speaker: {$this->getSpeaker()}
            Description: {$this->getDescription()}
            Track: {$this->getTrack()}
            Time Span: {$this->getTimeSpan()->toString()}
            CONTENT;
    }

    public function getMetadata(): Metadata
    {
        return new Metadata([
            ...parent::getMetadata()->getArrayCopy(),
            'speaker' => $this->getSpeaker(),
            'description' => $this->getDescription(),
            'track' => $this->getTrack(),
        ]);
    }
}
