<?php

declare(strict_types=1);

namespace App\UseCases\DetectFrauds\DTO;

readonly class DetectFraudsDTO
{
    public array $rawOrders;

    public function __construct(array $orders)
    {
        $this->rawOrders = $orders;
    }
}
