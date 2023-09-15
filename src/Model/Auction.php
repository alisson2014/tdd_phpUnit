<?php

declare(strict_types=1);

namespace Alura\Auction\Model;

class Auction
{
    /** @var Bid[] */
    private array $bids = [];

    public function __construct(
        private string $description
    ) {
    }

    public function receivesBid(Bid $bid): void
    {
        $this->bids[] = $bid;
    }

    /**
     * @return Bid[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }
}
