<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Infrastructure\Doctrine\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private readonly \DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\Column(type: 'text')]
        private readonly string $text,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string $authorName,
        #[ORM\Column(type: 'float')]
        private readonly float $sentimentScore,
        #[ORM\ManyToOne(targetEntity: Episode::class, inversedBy: 'feedbacks')]
        private Episode $episode,
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getSentimentScore(): float
    {
        return $this->sentimentScore;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }
}
