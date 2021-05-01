<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameComputerLogicTest extends TestCase
{
    /**
     * Try to create the dicehand class.
     */
    public function testCreateTheComputerLogicClass()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testLargeStraightFullMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 3, 4, 5, 6];
        $combinations = [];

        $dicesToKeep = $controller->straight($dices, $combinations);
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testLargeStraightFourMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 3, 4, 5, 2];
        $combinations = [];

        $dicesToKeep = $controller->straight($dices, $combinations);
        $exp = [0, 1, 2, 3];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testSmallStraightFullMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 3, 4, 5, 1];
        $combinations = [];

        $dicesToKeep = $controller->straight($dices, $combinations);
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testSmallStraightFourMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 3, 4, 1, 2];
        $combinations = [];

        $dicesToKeep = $controller->straight($dices, $combinations);
        $exp = [0, 1, 2, 3];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testTwoPairMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 2, 4, 4, 2];
        $summary = array_count_values($dices);
        $numbers = [4, 2];

        $dicesToKeep = $controller->minTwoPair($summary, $dices, $numbers);
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testTwoPairNoMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 4, 4, 4, 4];
        $summary = array_count_values($dices);
        $numbers = [4, 2];

        $dicesToKeep = $controller->minTwoPair($summary, $dices, $numbers);
        $exp = [];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPairMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 2, 4, 4, 2];
        $summary = array_count_values($dices);
        $number = 4;

        $dicesToKeep = $controller->pair($summary, $dices, $number);
        $exp = [2, 3];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPairNoMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 4, 4, 4, 4];
        $summary = array_count_values($dices);
        $number = 2;

        $dicesToKeep = $controller->pair($summary, $dices, $number);
        $exp = [];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testOneNumberMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 2, 4, 4, 2];
        $summary = array_count_values($dices);
        $number = 2;

        $dicesToKeep = $controller->oneNumber($summary, $dices, $number);
        $exp = [0, 1, 4];
        $this->assertEquals($exp, $dicesToKeep);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testOneNumberNoMatch()
    {
        $controller = new ComputerLogic();
        $this->assertInstanceOf("\App\Game\ComputerLogic", $controller);

        $dices = [2, 4, 4, 4, 4];
        $summary = array_count_values($dices);
        $number = 2;

        $dicesToKeep = $controller->oneNumber($summary, $dices, $number);
        $exp = [];
        $this->assertEquals($exp, $dicesToKeep);
    }
}
