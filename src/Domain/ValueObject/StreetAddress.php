<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class StreetAddress
{
    public function __construct(
        private string $value,
    ) {
        $this->value = $this->sanitizeAddress($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function sanitizeAddress(string $address): string
    {
        $address = strtolower(trim($address));
        $address = preg_replace('/st\./', 'street', $address);
        $address = preg_replace('/rd\./', 'road', $address);
        return trim($address);
    }
}
