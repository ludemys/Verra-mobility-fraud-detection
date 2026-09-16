<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\State;
use App\Domain\ValueObject\StreetAddress;

class Order
{
    public function __construct(
        private int $orderId,
        private int $dealId,
        private Email $email,
        private StreetAddress $streetAddress,
        private string $city,
        private State $state,
        private string $creditCard,
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getDealId(): int
    {
        return $this->dealId;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getStreetAddress(): StreetAddress
    {
        return $this->streetAddress;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getCreditCard(): string
    {
        return $this->creditCard;
    }
}
