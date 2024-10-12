<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

final readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
}
