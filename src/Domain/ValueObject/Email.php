<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class Email
{
    public function __construct(
        private string $value,
    ) {
        $this->value = $this->sanitizeEmail($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function sanitizeEmail(string $email): string
    {
        $email = strtolower(trim($email));
        $email = preg_replace('/\+.*@/', '@', $email);
        $email = preg_replace('/\.(?=[^@]*@)/', '', $email);

        if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email address: %s', $email));
        }

        return trim($email);
    }
}
