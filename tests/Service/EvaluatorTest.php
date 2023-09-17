<?php

declare(strict_types=1);

namespace Alura\Auction\Tests\Service;

use Alura\Auction\Model\Auction;
use Alura\Auction\Model\Bid;
use Alura\Auction\Model\User;
use Alura\Auction\Service\Evaluator;
use DomainException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class EvaluatorTest extends TestCase
{
    /** @var Evaluator */
    private Evaluator $auctioneer;

    protected function setUp(): void
    {
        $this->auctioneer = new Evaluator();
    }

    #[DataProvider('auctionInRandomOrder')]
    #[DataProvider('auctionInAscendingOrder')]
    #[DataProvider('auctionInDescendingOrder')]
    public function testReturnLowerValue(Auction $auction): void
    {
        // Act - When
        $this->auctioneer->evaluate($auction);
        $lowerValue = $this->auctioneer->getLowerValue();

        // Assert - Then
        self::assertEquals(1700, $lowerValue);
    }

    #[DataProvider('auctionInRandomOrder')]
    #[DataProvider('auctionInAscendingOrder')]
    #[DataProvider('auctionInDescendingOrder')]
    public function testReturnHighestValue(Auction $auction): void
    {
        // Act - When
        $this->auctioneer->evaluate($auction);
        $highestValue = $this->auctioneer->getGreaterValue();

        // Assert - Then
        self::assertEquals(2500, $highestValue);
    }

    #[DataProvider('auctionInRandomOrder')]
    #[DataProvider('auctionInAscendingOrder')]
    #[DataProvider('auctionInDescendingOrder')]
    public function testFindHighestBids(Auction $auction): void
    {

        $this->auctioneer->evaluate($auction);

        $highestBids = $this->auctioneer->getHighestBids();
        self::assertCount(3, $highestBids);
        self::assertEquals(2500, $highestBids[0]);
        self::assertEquals(2000, $highestBids[1]);
        self::assertEquals(1700, $highestBids[2]);
    }

    public function testEmptyAuctionCantBeEvaluated()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage('Não é possível avaliar leilão vazio');

        $auction = new Auction('Fusca Azul');
        $this->auctioneer->evaluate($auction);
    }

    public function testEndedAuctionCantBeEvaluated()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage('Leilão já finalizado');

        $auction = new Auction('Fiat 147 0KM');
        $auction->receivesBid(new Bid(new User('Teste'), 2000));

        $auction->ends();

        $this->auctioneer->evaluate($auction);
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
            'ascending-order' => [$auction]
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
            'descending-order' => [$auction]
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
            'random-order' => [$auction]
        ];
    }
}
