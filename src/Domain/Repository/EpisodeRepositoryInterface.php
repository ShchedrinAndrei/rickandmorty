<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Episode;
use Doctrine\Persistence\ObjectRepository;

interface EpisodeRepositoryInterface extends ObjectRepository
{
    public function findOneWithFeedbacks(int $episodeId): ?Episode;
}
