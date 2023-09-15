<?php

namespace Alura\Auction\Tests\Service;

use Alura\Auction\Model\Auction;
use Alura\Auction\Model\Bid;
use Alura\Auction\Model\User;
use Alura\Auction\Service\Evaluator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class EvaluatorTest extends TestCase
{
    #[DataProvider('auctionInRandomOrder')]
    #[DataProvider('auctionInAscendingOrder')]
    #[DataProvider('auctionInDescendingOrder')]
    public function testReturnLowerValue(Auction $auction)
    {
        $auctioneer = new Evaluator();

        // Act - When
        $auctioneer->evaluate($auction);
        $lowerValue = $auctioneer->getLowerValue();

        // Assert - Then
        self::assertEquals(1700, $lowerValue);
    }

    #[DataProvider('auctionInRandomOrder')]
    #[DataProvider('auctionInAscendingOrder')]
    #[DataProvider('auctionInDescendingOrder')]
    public function testReturnHighestValue(Auction $auction)
    {
        $auctioneer = new Evaluator();

        // Act - When
        $auctioneer->evaluate($auction);
        $highestValue = $auctioneer->getGreaterValue();

        // Assert - Then
        self::assertEquals(2500, $highestValue);
    }

    #[DataProvider('auctionInRandomOrder')]
    #[DataProvider('auctionInAscendingOrder')]
    #[DataProvider('auctionInDescendingOrder')]
    public function testFindHighestBids(Auction $auction)
    {
        $auctioneer = new Evaluator();
        $auctioneer->evaluate($auction);

        $highestBids = $auctioneer->getHighestBids();
        self::assertCount(3, $highestBids);
        self::assertEquals(2500, $highestBids[0]);
        self::assertEquals(2000, $highestBids[1]);
        self::assertEquals(1700, $highestBids[2]);
    }

    public static function auctionInAscendingOrder(): array
    {
        $auction = new Auction('Fiat 147 0km');

        $maria = new User('Maria');
        $joao = new User('Joao');
        $ana = new User('Ana');

        $auction->receivesBid(new Bid($ana, 1700));
        $auction->receivesBid(new Bid($joao, 2000));
        $auction->receivesBid(new Bid($maria, 2500));

        return [
            [$auction]
        ];
    }

    public static function auctionInDescendingOrder(): array
    {
        $auction = new Auction('Fiat 147 0km');

        $maria = new User('Maria');
        $joao = new User('Joao');
        $ana = new User('Ana');

        $auction->receivesBid(new Bid($maria, 2500));
        $auction->receivesBid(new Bid($joao, 2000));
        $auction->receivesBid(new Bid($ana, 1700));

        return [
            [$auction]
        ];
    }

    public static function auctionInRandomOrder(): array
    {
        $auction = new Auction('Fiat 147 0km');

        $maria = new User('Maria');
        $joao = new User('Joao');
        $ana = new User('Ana');

        $auction->receivesBid(new Bid($joao, 2000));
        $auction->receivesBid(new Bid($maria, 2500));
        $auction->receivesBid(new Bid($ana, 1700));

        return [
            [$auction]
        ];
    }
}
