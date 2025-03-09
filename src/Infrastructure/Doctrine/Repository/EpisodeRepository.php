<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Episode;
use App\Domain\Repository\EpisodeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Episode|null find(int $id, $lockMode = null, $lockVersion = null)
 * @method Episode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Episode[]    findAll()
 * @method Episode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Episode>
 */
class EpisodeRepository extends ServiceEntityRepository implements EpisodeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    public function findOneWithFeedbacks(int $episodeId): ?Episode
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.feedbacks', 'f')
            ->addSelect('f')
            ->where('e.id = :id')
            ->setParameter('id', $episodeId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
