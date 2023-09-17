<?php

declare(strict_types=1);

namespace Alura\Auction\Model;

class Auction
{
    /** @var Bid[] */
    private array $bids = [];
    private bool $status = true;

    public function __construct(
        private string $description
    ) {
    }

    public function receivesBid(Bid $bid): void
    {
        if (!empty($this->bids) && $this->isFromTheLastUser($bid)) {
            throw new \DomainException('Usuário não pode propor 2 lances seguidos');
        }

        $totalBidsPerUser = $this->numberOfBidsPerUser($bid->getUser());

        if ($totalBidsPerUser >= 5) {
            throw new \DomainException('Usuário não propor mais de 5 lances por leilão');
        }

        $this->bids[] = $bid;
    }

    public function ends(): void
    {
        $this->status = false;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    /** @return Bid[] */
    public function getBids(): array
    {
        return $this->bids;
    }

    private function isFromTheLastUser(Bid $bid): bool
    {
        $lastBid = $this->bids[array_key_last($this->bids)];
        return $bid->getUser() === $lastBid->getUser();
    }

    private function numberOfBidsPerUser(User $user): int
    {
        $returnTotal = function (int $accumulatedTotal, Bid $currentBid) use ($user): int {
            if ($currentBid->getUser() === $user) {
                return $accumulatedTotal + 1;
            }
            return $accumulatedTotal;
        };

        $totalBidsPerUser = array_reduce($this->bids, $returnTotal, 0);
        return $totalBidsPerUser;
    }
}
