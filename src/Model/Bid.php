<?php

declare(strict_types=1);

namespace Alura\Auction\Model;

class Bid
{
    public function __construct(
        private User $user,
        private float $value
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
