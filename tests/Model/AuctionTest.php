<?php

declare(strict_types=1);

namespace Alura\Auction\Tests\Model;

use Alura\Auction\Model\Auction;
use Alura\Auction\Model\Bid;
use Alura\Auction\Model\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AuctionTest extends TestCase
{
    public function testAuctionShouldNotAcceptMoreThanFiveBidsPerUser()
    {
        $auction = new Auction('Brasília Amarela');
        $joao = new User('João');
        $maria = new User('Maria');

        $auction->receivesBid(new Bid($joao, 1000));
        $auction->receivesBid(new Bid($maria, 1500));
        $auction->receivesBid(new Bid($joao, 2000));
        $auction->receivesBid(new Bid($maria, 2500));
        $auction->receivesBid(new Bid($joao, 3000));
        $auction->receivesBid(new Bid($maria, 3500));
        $auction->receivesBid(new Bid($joao, 4000));
        $auction->receivesBid(new Bid($maria, 4500));
        $auction->receivesBid(new Bid($joao, 5000));
        $auction->receivesBid(new Bid($maria, 5500));
        $auction->receivesBid(new Bid($joao, 6000));

        $lastBid = $auction->getBids()[array_key_last($auction->getBids())];

        self::assertCount(10, $auction->getBids());
        self::assertEquals(5500, $lastBid->getValue());
    }

    public function testAuctionShouldNotReceiveRepeatedBids()
    {
        $auction = new Auction('Variante');
        $ana = new User('Ana');

        $auction->receivesBid(new Bid($ana, 1000));
        $auction->receivesBid(new Bid($ana, 1500));

        $firstBid = $auction->getBids()[0];

        self::assertCount(1, $auction->getBids());
        self::assertEquals(1000, $firstBid->getValue());
    }

    #[DataProvider('bidGenerator')]
    public function testAuctionMustReceiveBids(
        int $numberOfBids,
        Auction $auction,
        array $values
    ): void {
        self::assertCount($numberOfBids, $auction->getBids());
        foreach ($values as $i => $expectedValue) {
            $bidValue = $auction->getBids()[$i]->getValue();
            self::assertEquals($expectedValue, $bidValue);
        }
    }

    public static function bidGenerator(): array
    {
        $joao = new User('João');
        $maria = new User('Maria');

        $auctionWithTwoBids = new Auction('Fiat 147 0KM');
        $auctionWithTwoBids->receivesBid(new Bid($joao, 1000));
        $auctionWithTwoBids->receivesBid(new Bid($maria, 2000));

        $auctionWithOneBid = new Auction('Fusca 1972 0KM');
        $auctionWithOneBid->receivesBid(new Bid($maria, 5000));

        return [
            'two-bids' => [2, $auctionWithTwoBids, [1000, 2000]],
            'one-bid' => [1, $auctionWithOneBid, [5000]]
        ];
    }
}
