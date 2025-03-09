<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Feedback;

/**
 * @method Feedback|null find(int $id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface FeedbackRepositoryInterface
{
}
