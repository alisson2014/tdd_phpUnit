<?php

namespace Alura\Auction\Tests\Service;

use Alura\Auction\Model\Auction;
use Alura\Auction\Model\Bid;
use Alura\Auction\Model\User;
use Alura\Auction\Service\Evaluator;
use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
    public function testReturnHighestValue()
    {
        // Arrange - Given
        $auction = new Auction('Fiat 147 0KM');

        $maria = new User('Maria');
        $joao = new User('João');

        $auction->receivesBid(new Bid($maria, 2500));
        $auction->receivesBid(new Bid($joao, 17000));
        $auction->receivesBid(new Bid($joao, 10000));
        $auction->receivesBid(new Bid($joao, 1040));
        $auction->receivesBid(new Bid($joao, 12000));

        $auctioneer = new Evaluator();

        // Act - When
        $auctioneer->evaluate($auction);
        $highestValue = $auctioneer->getGreaterValue();
        $lowerValue = $auctioneer->getLowerValue();

        // Assert - Then
        self::assertEquals(1040, $lowerValue);
        self::assertEquals(17000, $highestValue);
    }

    public function testFindHighestBids()
    {
        $auction = new Auction('Fiat 147 0KM');

        $joao = new User('João');
        $maria = new User('Maria');
        $ana = new User('Ana');
        $jorge = new User('Jorge');

        $auction->receivesBid(new Bid($ana, 1500));
        $auction->receivesBid(new Bid($joao, 1000));
        $auction->receivesBid(new Bid($maria, 2000));
        $auction->receivesBid(new Bid($jorge, 1700));

        $auctioneer = new Evaluator();
        $auctioneer->evaluate($auction);

        $highestBids = $auctioneer->getHighestBids();
        self::assertCount(3, $highestBids);
        self::assertEquals(2000, $highestBids[0]);
        self::assertEquals(1700, $highestBids[1]);
        self::assertEquals(1500, $highestBids[2]);
    }
}
