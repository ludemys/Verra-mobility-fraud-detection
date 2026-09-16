<?php

declare(strict_types=1);

namespace App\Tests\UseCases\DetectFrauds\Service;

use App\UseCases\DetectFrauds\DTO\DetectFraudsDTO;
use App\UseCases\DetectFrauds\Exception\DetectFraudsCollectionCreatedException;
use App\UseCases\DetectFrauds\Service\DetectFrauds;
use PHPUnit\Framework\TestCase;

final class DetectFraudsTest extends TestCase
{
    public function testItDetectsFraudulentOrdersByEmailAndStreetData(): void
    {
        $orders = [
            [
                'orderId' => 1,
                'dealId' => 100,
                'email' => 'valid@example.com',
                'streetAddress' => '1 Valid Street',
                'city' => 'Chicago',
                'state' => 'IL',
                'creditCard' => '1111-1111-1111-1111',
            ],
            [
                'orderId' => 2,
                'dealId' => 200,
                'email' => 'Same-ema.il@example.com',
                'streetAddress' => '2 First Street',
                'city' => 'Chicago',
                'state' => 'IL',
                'creditCard' => '2222-2222-2222-2222',
            ],
            [
                'orderId' => 3,
                'dealId' => 200,
                'email' => 'samE-email@exAmple.cOm',
                'streetAddress' => '3 Second Street',
                'city' => 'Chicago',
                'state' => 'IL',
                'creditCard' => '3333-3333-3333-3333',
            ],
            [
                'orderId' => 4,
                'dealId' => 300,
                'email' => 'first-address@example.com',
                'streetAddress' => '4 Shared St.',
                'city' => 'New York',
                'state' => 'NY',
                'creditCard' => '4444-4444-4444-4444',
            ],
            [
                'orderId' => 5,
                'dealId' => 300,
                'email' => 'second-address@example.com',
                'streetAddress' => '4 Shared Street',
                'city' => 'New York',
                'state' => 'NY',
                'creditCard' => '5555-5555-5555-5555',
            ],
        ];

        $fraudulentOrderIds = (new DetectFrauds())->detect(
            new DetectFraudsDTO(orders: $orders),
        );

        self::assertSame([2, 3, 4, 5], $fraudulentOrderIds);
    }

    public function testFailsWhenInvalidOrdersAreProvided(): void
    {
        $orders = [
            [
                'orderId' => 'some wrong ID',
                'dealId' => 100,
                'email' => 'valid@example.com',
                'streetAddress' => '1 Valid Street',
                'city' => 'Chicago',
                'state' => 'IL',
                'creditCard' => '1111-1111-1111-1111',
            ],
        ];

        $this->expectException(DetectFraudsCollectionCreatedException::class);

        $fraudulentOrderIds = (new DetectFrauds())->detect(
            new DetectFraudsDTO(orders: $orders),
        );
    }
}
