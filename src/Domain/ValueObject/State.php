<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class State
{
    public function __construct(
        private string $value,
    ) {
        $this->value = $this->sanitizeState($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function sanitizeState(string $state): string
    {
        $state = strtolower(trim($state));

        $abbreviatedStates = [
            'il' => 'illinois',
            'ca' => 'california',
            'ny' => 'new york',
        ];

        return array_key_exists($state, $abbreviatedStates)
            ? $abbreviatedStates[$state]
            : $state
        ;
    }
}
