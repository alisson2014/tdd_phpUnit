<?php

use Alura\Auction\Model\Auction;
use Alura\Auction\Model\Bid;
use Alura\Auction\Model\User;
use Alura\Auction\Service\Evaluator;

require 'vendor/autoload.php';

// Arrange - Given
$auction = new Auction('Fiat 147 0KM');

$maria = new User('Maria');
$joao = new User('João');

$auction->receivesBid(new Bid($joao, 2000));
$auction->receivesBid(new Bid($maria, 2500));

$leiloeiro = new Evaluator();

// Act - When
$leiloeiro->avalia($auction);

$maiorValor = $leiloeiro->getGreaterValue();

// Assert - Then
$valorEsperado = 2500;

if ($valorEsperado == $maiorValor) {
    echo "TESTE OK";
} else {
    echo "TESTE FALHOU";
}
