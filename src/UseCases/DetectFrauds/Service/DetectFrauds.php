<?php

namespace App\UseCases\DetectFrauds\Service;

use App\Domain\Collection\OrdersCollection;
use App\Domain\Entity\Order;
use App\UseCases\DetectFrauds\DTO\DetectFraudsDTO;
use App\UseCases\DetectFrauds\Exception\DetectFraudsCollectionCreatedException;

class DetectFrauds
{
    /**
     * @return int[]
     */
    public function detect(DetectFraudsDTO $dto): array
    {
        try {
            $orders = OrdersCollection::fromArray($dto->rawOrders);
            $fraudulentOrderIds = $orders->getFraudulentOrderIds();

        } catch (\Throwable) {
            throw new DetectFraudsCollectionCreatedException('Invalid orders raw data');
        }

        sort($fraudulentOrderIds);
        return $fraudulentOrderIds;
    }
}
