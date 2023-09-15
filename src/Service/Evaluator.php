<?php

declare(strict_types=1);

namespace Alura\Auction\Service;

use Alura\Auction\Model\Auction;

class Evaluator
{
    private float $highestValue;
    private float $lowerValue;
    private array $highestBids;

    public function evaluate(Auction $auction): void
    {
        $bidValues = array_map(function ($bid) {
            return $bid->getValue();
        }, $auction->getBids());

        $this->lowerValue = min($bidValues);
        $this->highestValue = max($bidValues);

        usort($bidValues, function (float $bid1, float $bid2) {
            return $bid2 - $bid1;
        });

        $this->highestBids = array_slice($bidValues, 0, 3);
    }

    public function getGreaterValue(): float
    {
        return $this->highestValue;
    }

    public function getLowerValue(): float
    {
        return $this->lowerValue;
    }

    public function getHighestBids(): array
    {
        return $this->highestBids;
    }
}
