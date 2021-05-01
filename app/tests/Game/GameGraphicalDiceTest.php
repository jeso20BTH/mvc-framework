<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameGraphicalDiceTest extends TestCase
{
    /**
     * Try to create the dice class.
     */
    public function testCreateTheDiceClass()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);
    }

    /**
     * Check that the last roll is graphicly equal to given array.
     */
    public function testGraphicalDiceValueOne()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);

        $lastRoll = $controller->getLastRoll();

        $this->assertEmpty($lastRoll);

        $exp = 1;
        $controller->setLastRoll($exp);
        $dice = $controller->getLastRoll();

        $this->assertEquals($dice, $exp);

        $graph = $controller->grapicalLastRoll();
        $exp = [
            [
                "amount" => 1,
                "spacing" => "center"
            ],
        ];

        $this->assertEquals($graph, $exp);
    }

    /**
     * Check that the last roll is graphicly equal to given array.
     */
    public function testGraphicalDiceValueTwo()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);

        $lastRoll = $controller->getLastRoll();

        $this->assertEmpty($lastRoll);

        $exp = 2;
        $controller->setLastRoll($exp);
        $dice = $controller->getLastRoll();

        $this->assertEquals($dice, $exp);

        $graph = $controller->grapicalLastRoll();
        $exp = [
            [
                "amount" => 1,
                "spacing" => "start"
            ],
            [
                "amount" => 1,
                "spacing" => "end"
            ]
        ];

        $this->assertEquals($graph, $exp);
    }

    /**
     * Check that the last roll is graphicly equal to given array.
     */
    public function testGraphicalDiceValueThree()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);

        $lastRoll = $controller->getLastRoll();

        $this->assertEmpty($lastRoll);

        $exp = 3;
        $controller->setLastRoll($exp);
        $dice = $controller->getLastRoll();

        $this->assertEquals($dice, $exp);

        $graph = $controller->grapicalLastRoll();
        $exp = [
            [
                "amount" => 1,
                "spacing" => "start"
            ],
            [
                "amount" => 1,
                "spacing" => "center"
            ],
            [
                "amount" => 1,
                "spacing" => "end"
            ]
        ];

        $this->assertEquals($graph, $exp);
    }

    /**
     * Check that the last roll is graphicly equal to given array.
     */
    public function testGraphicalDiceValueFour()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);

        $lastRoll = $controller->getLastRoll();

        $this->assertEmpty($lastRoll);

        $exp = 4;
        $controller->setLastRoll($exp);
        $dice = $controller->getLastRoll();

        $this->assertEquals($dice, $exp);

        $graph = $controller->grapicalLastRoll();
        $exp = [
            [
                "amount" => 2,
                "spacing" => "between"
            ],
            [
                "amount" => 2,
                "spacing" => "between"
            ]
        ];

        $this->assertEquals($graph, $exp);
    }

    /**
     * Check that the last roll is graphicly equal to given array.
     */
    public function testGraphicalDiceValueFive()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);

        $lastRoll = $controller->getLastRoll();

        $this->assertEmpty($lastRoll);

        $exp = 5;
        $controller->setLastRoll($exp);
        $dice = $controller->getLastRoll();

        $this->assertEquals($dice, $exp);

        $graph = $controller->grapicalLastRoll();
        $exp = [
            [
                "amount" => 2,
                "spacing" => "between"
            ],
            [
                "amount" => 1,
                "spacing" => "center"
            ],
            [
                "amount" => 2,
                "spacing" => "between"
            ]
        ];

        $this->assertEquals($graph, $exp);
    }

    /**
     * Check that the last roll is graphicly equal to given array.
     */
    public function testGraphicalDiceValueSix()
    {
        $controller = new GraphicalDice(6);
        $this->assertInstanceOf("\App\Game\GraphicalDice", $controller);

        $lastRoll = $controller->getLastRoll();

        $this->assertEmpty($lastRoll);

        $exp = 6;
        $controller->setLastRoll($exp);
        $dice = $controller->getLastRoll();

        $this->assertEquals($dice, $exp);

        $graph = $controller->grapicalLastRoll();
        $exp = [
            [
                "amount" => 2,
                "spacing" => "between"
            ],
            [
                "amount" => 2,
                "spacing" => "between"
            ],
            [
                "amount" => 2,
                "spacing" => "between"
            ]
        ];

        $this->assertEquals($graph, $exp);
    }
}
