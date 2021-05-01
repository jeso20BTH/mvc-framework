<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameComputerTest extends TestCase
{
    /**
     * Try to create the dice class.
     */
    public function testCreateTheComputerClass()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);
    }

    /**
     * Check that the last roll is null before rolling.
     */
    public function testDiceLastRollStraight()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([1, 2, 3, 4, 5]);

        $controller->setDiceHand($diceHand);

        $dices = $controller->keepLogic();
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $dices);
    }

    /**
     * Check that the last roll is null before rolling.
     */
    public function testDiceLastRollFullHouse()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([2, 2, 3, 3, 5]);

        $controller->setDiceHand($diceHand);

        $dices = $controller->keepLogic();
        $exp = [0, 1, 2, 3];
        $this->assertEquals($exp, $dices);
    }

    /**
     * Check that the last roll is null before rolling.
     */
    public function testDiceLastRollYatzy()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([2, 2, 2, 2, 2]);

        $controller->setDiceHand($diceHand);

        $dices = $controller->keepLogic();
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $dices);
    }

    /**
     * Check that the last roll is null before rolling.
     */
    public function testDiceLastNumber()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([2, 2, 2, 3, 5]);

        $controller->setDiceHand($diceHand);

        $dices = $controller->keepLogic();
        $exp = [0, 1, 2];
        $this->assertEquals($exp, $dices);
    }

    /**
     * Check that the last roll is null before rolling.
     */
    public function testDiceTwoPair()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([2, 2, 3, 3, 5]);

        $controller->setDiceHand($diceHand);

        $controller->setScore("Full house", 28);
        $dices = $controller->keepLogic();
        $exp = [0, 1, 2, 3];
        $this->assertEquals($exp, $dices);
    }

    /**
     * Check that the last roll is null before rolling.
     */
    public function testPlacementYatzy()
    {
        $controller = new Computer("name", "computer");
        $this->assertInstanceOf("\App\Game\Computer", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([2, 2, 2, 2, 2]);

        $controller->setDiceHand($diceHand);


        $score = $controller->place("Yatzy");
        $exp = 50;
        $this->assertEquals($exp, $score["sum"]);
    }
}
