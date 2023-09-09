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
        $auctioneer->avalia($auction);
        $highestValue = $auctioneer->getGreaterValue();
        $lowerValue = $auctioneer->getLowerValue();

        // Assert - Then
        self::assertEquals(1040, $lowerValue);
        self::assertEquals(17000, $highestValue);
    }

    public function testReturnLowerValue()
    {
        $auction = new Auction('Fiat 147 0KM');

        $cleber = new User('Cleber');
        $fernando = new User('Fernando');

        $auction->receivesBid(new Bid($cleber, 9000));
        $auction->receivesBid(new Bid($fernando, 11000));
        $auction->receivesBid(new Bid($fernando, 1000));
        $auction->receivesBid(new Bid($fernando, 10000));

        $auctioneer = new Evaluator();

        // Act - When
        $auctioneer->avalia($auction);
        $highestValue = $auctioneer->getGreaterValue();
        $lowerValue = $auctioneer->getLowerValue();

        // Assert - Then
        self::assertEquals(11000, $highestValue);
        self::assertEquals(1000, $lowerValue);
    }
}
