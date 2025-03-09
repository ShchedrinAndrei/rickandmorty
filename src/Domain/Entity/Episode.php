<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Infrastructure\Doctrine\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_external_id', columns: ['external_id'])]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Ignore]
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'episode', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $feedbacks;

    public function __construct(
        #[ORM\Column(type: 'integer', unique: true, options: ['unique' => true])]
        private readonly int $externalId,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string $name,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string $airDate,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string $episode,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string $url,
        #[ORM\Column(type: 'string', length: 255)]
        private readonly string $created,
    ) {
        $this->feedbacks = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirDate(): string
    {
        return $this->airDate;
    }

    public function getEpisode(): string
    {
        return $this->episode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks->add($feedback);
        }

        return $this;
    }
}
