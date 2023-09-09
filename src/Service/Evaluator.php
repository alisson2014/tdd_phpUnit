<?php

namespace Alura\Auction\Service;

use Alura\Auction\Model\Auction;

class Evaluator
{
    private float $highestValue;

    public function avalia(Auction $auction): void
    {
        $bids = $auction->getBids();
        $lastPosition = count($bids) - 1;
        $lastMove = $bids[$lastPosition];
        $this->highestValue = $lastMove->getValue();
    }

    public function getGreaterValue(): float
    {
        return $this->highestValue;
    }
}
