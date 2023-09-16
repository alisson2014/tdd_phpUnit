<?php

declare(strict_types=1);

namespace Alura\Auction\Service;

use Alura\Auction\Model\Auction;
use Alura\Auction\Model\Bid;

class Evaluator
{
    private float $highestValue;
    private float $lowerValue;
    private array $highestBids;

    public function evaluate(Auction $auction): void
    {
        $returnBid = function (Bid $bid): float {
            return $bid->getValue();
        };

        $bidValues = array_map($returnBid, $auction->getBids());

        $this->lowerValue = min($bidValues);
        $this->highestValue = max($bidValues);

        $sortBids = function (float $bid1, float $bid2): float {
            return $bid2 - $bid1;
        };

        usort($bidValues, $sortBids);

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
