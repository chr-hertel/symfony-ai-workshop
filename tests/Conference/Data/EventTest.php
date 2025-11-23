<?php

namespace App\Tests\Conference\Data;

use App\Conference\Data\Event;
use App\Conference\Data\Slot;
use App\Conference\Data\TimeSpan;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Store\Document\EmbeddableDocumentInterface;

final class EventTest extends TestCase
{
    public function testDeterministicUuidGeneration(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Event::class, EmbeddableDocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement EmbeddableDocumentInterface');
        }

        $eventA = $this->createEvent('My Event', '2025-01-10 09:00:00', '2025-01-10 10:00:00');
        $eventB = $this->createEvent('My Event', '2025-01-10 09:00:00', '2025-01-10 10:00:00');
        $eventC = $this->createEvent('My Event', '2025-01-10 10:00:00', '2025-01-10 11:00:00');
        $eventD = $this->createEvent('Other Event', '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $this->assertSame((string) $eventA->getId(), (string) $eventB->getId(), 'Same title and time should produce identical v5 UUID');
        $this->assertNotSame((string) $eventA->getId(), (string) $eventC->getId(), 'Same title but different time should produce different v5 UUID');
        $this->assertNotSame((string) $eventA->getId(), (string) $eventD->getId(), 'Different title and time should produce different v5 UUID');
    }

    public function testMetadata(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Event::class, EmbeddableDocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement EmbeddableDocumentInterface');
        }

        $event = $this->createEvent('Conference Opening', '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $expected = [
            'title' => 'Conference Opening',
            'start_time' => '2025-01-10 09:00:00',
            'end_time' => '2025-01-10 10:00:00',
        ];

        $this->assertSame($expected, $event->getMetadata()->getArrayCopy(), 'Metadata should contain expected values');
    }

    public function testContent(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Event::class, EmbeddableDocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement EmbeddableDocumentInterface');
        }

        $event = $this->createEvent('Keynote', '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $expected = <<<EOT
            Title: Keynote
            Time Span: Jan 10, 09:00 - 10:00
            EOT;

        $this->assertSame($expected, $event->getContent(), 'Content should match expected format');
    }

    private function createEvent(string $title, string $start, string $end): Event
    {
        $timeSpan = new TimeSpan(
            new \DateTimeImmutable($start, new \DateTimeZone('Europe/Paris')),
            new \DateTimeImmutable($end, new \DateTimeZone('Europe/Paris')),
        );
        $slot = new Slot($timeSpan);

        return new Event($title, $timeSpan, $slot);
    }
}
