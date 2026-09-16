<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Order;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\State;
use App\Domain\ValueObject\StreetAddress;

class OrdersCollection
{
    private array $orders = [];

    /**
     * @param Order[] $orders
     */
    public function __construct(array $orders = [])
    {
        $this->orders = $orders;
    }

    public static function fromArray(array $rawOrders): self
    {
        $orders = [];

        foreach ($rawOrders as $orderData) {
            $orders[] = new Order(
                orderId: $orderData['orderId'],
                dealId: $orderData['dealId'],
                email: new Email($orderData['email']),
                streetAddress: new StreetAddress($orderData['streetAddress']),
                city: strtolower($orderData['city']),
                state: new State($orderData['state']),
                creditCard: strtolower($orderData['creditCard']),
            );
        }

        return new self($orders);
    }

    public function addOrder(Order $order): void
    {
        $this->orders[] = $order;
    }

    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @return int[]
     */
    public function getFraudulentOrderIds(): array
    {
        $emailGroups = [];
        $addressGroups = [];

        foreach ($this->orders as $order) {
            $emailGroups = $this->registerGroup(
                groups: $emailGroups,
                key: $this->getEmailGroupKey($order),
                creditCard: $order->getCreditCard(),
            );

            $addressGroups = $this->registerGroup(
                groups: $addressGroups,
                key: $this->getAddressGroupKey($order),
                creditCard: $order->getCreditCard(),
            );
        }

        $fraudulentOrderIds = [];

        foreach ($this->orders as $order) {
            $emailGroup = $emailGroups[$this->getEmailGroupKey($order)];
            $addressGroup = $addressGroups[$this->getAddressGroupKey($order)];

            if ($emailGroup['fraudulent'] || $addressGroup['fraudulent']) {
                $fraudulentOrderIds[] = $order->getOrderId();
            }
        }

        return $fraudulentOrderIds;
    }

    private function registerGroup(array $groups, string $key, string $creditCard): array
    {
        if (!array_key_exists($key, $groups)) {
            $groups[$key] = [
                'creditCard' => $creditCard,
                'fraudulent' => false,
            ];

            return $groups;
        }

        if ($groups[$key]['creditCard'] !== $creditCard) {
            $groups[$key]['fraudulent'] = true;
        }

        return $groups;
    }

    private function getEmailGroupKey(Order $order): string
    {
        return implode('-', [
            $order->getDealId(),
            $order->getEmail()->getValue(),
        ]);
    }

    private function getAddressGroupKey(Order $order): string
    {
        return implode('-', [
            $order->getDealId(),
            $order->getStreetAddress()->getValue(),
            $order->getCity(),
            $order->getState()->getValue(),
        ]);
    }
}
