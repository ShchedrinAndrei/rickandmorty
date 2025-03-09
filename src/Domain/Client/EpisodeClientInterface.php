<?php

declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Dto\EpisodePaginationDto;

interface EpisodeClientInterface
{
    public function getAll(): EpisodePaginationDto;
}
