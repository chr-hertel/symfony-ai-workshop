<?php

namespace App\Conference\Data;

use Symfony\AI\Store\Document\EmbeddableDocumentInterface;
use Symfony\AI\Store\Document\Metadata;
use Symfony\Component\Uid\Uuid;

class Event implements EmbeddableDocumentInterface
{
    private const string UUID_NAMESPACE = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';

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

    public function getId(): Uuid
    {
        return Uuid::v5(
            Uuid::fromString(self::UUID_NAMESPACE),
            $this->getTitle().$this->getTimeSpan()->toString(),
        );
    }

    public function getContent(): string
    {
        return <<<CONTENT
            Title: {$this->getTitle()}
            Time Span: {$this->getTimeSpan()->toString()}
            CONTENT;

    }

    public function getMetadata(): Metadata
    {
        return new Metadata([
            'title' => $this->getTitle(),
            'start_time' => $this->getTimeSpan()->getStart()->format('Y-m-d H:i:s'),
            'end_time' => $this->getTimeSpan()->getEnd()->format('Y-m-d H:i:s'),
        ]);
    }
}
