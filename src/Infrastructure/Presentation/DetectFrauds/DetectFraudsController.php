<?php

declare(strict_types=1);

namespace App\Infrastructure\Presentation\DetectFrauds;

use App\UseCases\DetectFrauds\DTO\DetectFraudsDTO;
use App\UseCases\DetectFrauds\Exception\DetectFraudsCollectionCreatedException;
use App\UseCases\DetectFrauds\Service\DetectFrauds;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class DetectFraudsController extends AbstractController
{
    public function __construct(
        private DetectFrauds $detectFraudsService,
    ) {
    }

    #[Route('/api/fraud-detection', name: 'api_frauds_detect', methods: ['POST'], format: 'json')]
    public function handle(#[MapRequestPayload] DetectFraudsDTO $dto): Response
    {
        try {
            $fraudulentOrderIds = $this->detectFraudsService->detect($dto);
        } catch (DetectFraudsCollectionCreatedException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->json(['fraudulentOrders' => $fraudulentOrderIds]);
    }
}
