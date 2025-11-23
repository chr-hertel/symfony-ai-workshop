<?php

namespace App\Tests\Conference\Data;

use App\Conference\Data\Slot;
use App\Conference\Data\Talk;
use App\Conference\Data\TimeSpan;
use App\Conference\Data\Track;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Store\Document\EmbeddableDocumentInterface;

final class TalkTest extends TestCase
{
    public function testDeterministicUuidGeneration(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Talk::class, EmbeddableDocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement EmbeddableDocumentInterface');
        }

        $talkA = $this->createTalk('My Talk', 'Alice', 'About stuff', Track::Symfony, '2025-01-10 09:00:00', '2025-01-10 10:00:00');
        $talkB = $this->createTalk('My Talk', 'Bob', 'Different description', Track::SensioLabs, '2025-01-10 09:00:00', '2025-01-10 10:00:00');
        $talkC = $this->createTalk('My Talk', 'Alice', 'About stuff', Track::Symfony, '2025-01-10 10:00:00', '2025-01-10 11:00:00');
        $talkD = $this->createTalk('Other Talk', 'Alice', 'About stuff', Track::Symfony, '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $this->assertSame((string) $talkA->getId(), (string) $talkB->getId(), 'Same title should produce identical v5 UUID despite other field differences');
        $this->assertNotSame((string) $talkA->getId(), (string) $talkC->getId(), 'Same title but differendt time should produce different v5 UUIDs');
        $this->assertNotSame((string) $talkA->getId(), (string) $talkD->getId(), 'Different title and time should produce different v5 UUID');
    }

    public function testMetadata(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Talk::class, EmbeddableDocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement EmbeddableDocumentInterface');
        }

        $talk = $this->createTalk('Opening Keynote', 'Dr. Key Note', 'Welcome to the conference', Track::Symfony, '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $expected = [
            'title' => 'Opening Keynote',
            'start_time' => '2025-01-10 09:00:00',
            'end_time' => '2025-01-10 10:00:00',
            'speaker' => 'Dr. Key Note',
            'description' => 'Welcome to the conference',
            'track' => Track::Symfony->value,
        ];

        $this->assertSame($expected, $talk->getMetadata()->getArrayCopy(), 'Metadata should contain expected values including talk-specific fields');
    }

    public function testContent(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Talk::class, EmbeddableDocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement EmbeddableDocumentInterface');
        }

        $talk = $this->createTalk('Great Talk', 'Jane Doe', 'Deep dive into something great', Track::UpsunSmile, '2025-01-10 13:00:00', '2025-01-10 14:00:00');

        $expected = <<<EOT
            Title: Great Talk
            Speaker: Jane Doe
            Description: Deep dive into something great
            Track: Track Upsun & Smile
            Time Span: Jan 10, 13:00 - 14:00
            EOT;

        $this->assertSame($expected, $talk->getContent(), 'Content should match expected format with all Talk fields');
    }

    private function createTalk(string $title, string $speaker, string $description, Track $track, string $start, string $end): Talk
    {
        $timeSpan = new TimeSpan(
            new \DateTimeImmutable($start, new \DateTimeZone('Europe/Paris')),
            new \DateTimeImmutable($end, new \DateTimeZone('Europe/Paris')),
        );
        $slot = new Slot($timeSpan);

        return new Talk($title, $speaker, $description, $timeSpan, $track, $slot);
    }
}
