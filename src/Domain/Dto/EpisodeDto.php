<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;

readonly class EpisodeDto
{
    public function __construct(
        private int $id,
        private string $name,
        #[SerializedName('air_date')]
        private string $airDate,
        private string $episode,
        private string $url,
        private string $created,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreated(): string
    {
        return $this->created;
    }
}
