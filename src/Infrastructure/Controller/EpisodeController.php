<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Dto\EpisodeFeedbackDto;
use App\Application\Handler\EpisodeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/episode')]
class EpisodeController extends AbstractController
{
    public function __construct(
        private readonly EpisodeHandler $handler,
    ) {
    }

    #[Route(path: '/', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        return $this->json($this->handler->getAll());
    }

    #[Route(path: '/{id}', methods: 'GET')]
    public function find(int $id): JsonResponse
    {
        $episode = $this->handler->findOne($id);

        return $this->json($episode);
    }

    #[Route(path: '/{id}/feedback', methods: 'POST')]
    public function feedback(int $id, #[MapRequestPayload] EpisodeFeedbackDto $dto): JsonResponse
    {
        $this->handler->review($id, $dto);

        return new JsonResponse(['result' => 'ok']);
    }
}
