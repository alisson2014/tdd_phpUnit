<?php

namespace Alura\Auction\Service;

use Alura\Auction\Model\Auction;

class Evaluator
{
    private float $highestValue;
    private float $lowerValue;

    public function avalia(Auction $auction): void
    {
        $bidValues = array_map(function ($bid) {
            return $bid->getValue();
        }, $auction->getBids());

        $this->lowerValue = min($bidValues);
        $this->highestValue = max($bidValues);
    }

    public function getGreaterValue(): float
    {
        return $this->highestValue;
    }

    public function getLowerValue(): float
    {
        return $this->lowerValue;
    }
}
