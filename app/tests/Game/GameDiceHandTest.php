<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameDiceHandTest extends TestCase
{
    /**
     * Try to create the dicehand class.
     */
    public function testCreateTheDiceHandClass()
    {
        $controller = new DiceHand(1, 6);
        $this->assertInstanceOf("\App\Game\DiceHand", $controller);
    }

    /**
     * Try to get the dice sum no roll
     */
    public function testdiceSumNoRoll()
    {
        $dices = 1;
        $controller = new DiceHand($dices, 6);
        $this->assertInstanceOf("\App\Game\DiceHand", $controller);

        $roll = $controller->getLastRoll();
        $this->assertEmpty($roll);

        $rollArray = $controller->getLastRollArray();
        $exp = [];
        $this->assertEquals($rollArray, $exp);

        $rollGraphic = $controller->getGraphicalRoll();
        $exp = [];
        $this->assertEquals($rollGraphic, $exp);

        $rollSum = $controller->getDiceSum();
        $this->assertEmpty($rollSum);
    }

    /**
     * Try to get the dice sum no roll
     */
    public function testdiceSumRollThreeDices()
    {
        $dices = 0;
        $roll = 6;
        $controller = new DiceHand($dices, 6);
        $this->assertInstanceOf("\App\Game\DiceHand", $controller);

        $dice = $this->createStub(GraphicalDice::class);

        $dice->method('roll')
             ->willReturn($roll);

        $dice->method('getLastRoll')
             ->willReturn($roll);

        $dice->method('grapicalLastRoll')
             ->willReturn([
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
             ]);

        $controller->addDice($dice);
        $controller->addDice($dice);
        $controller->addDice($dice);

        $controller->roll();

        $rolls = $controller->getLastRoll();
        $exp = "6, 6, 6";
        $this->assertEquals($rolls, $exp);

        $rollArray = $controller->getLastRollArray();
        $exp = [6, 6, 6];
        $this->assertEquals($rollArray, $exp);

        $rollGraphic = $controller->getGraphicalRoll();
        $exp = [
            [
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
            ],
            [
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
            ],
            [
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
            ]
        ];
        $this->assertEquals($rollGraphic, $exp);

        $rollSum = $controller->getDiceSum();
        $exp = $roll * 3;
        $this->assertEquals($rollSum, $exp);
    }

    /**
     * Try to get the dice sum no roll
     */
    public function testdiceSumRollSpecificThreeDices()
    {
        $dices = 0;
        $controller = new DiceHand($dices, 6);
        $this->assertInstanceOf("\App\Game\DiceHand", $controller);

        for ($i = 1; $i <= 6; $i++) {
            $dice = $this->createStub(GraphicalDice::class);

                $dice->method('roll')
                     ->willReturn(6);

                $dice->method('getLastRoll')
                     ->willReturn(1);

            $controller->addDice($dice);
        }

        $dicesToRoll = [0, 2, 4];

        $controller->rollSpecific($dicesToRoll);


        // $rollArray = $controller->getLastRollArray();
        // $exp = [6, 6, 6];
        // $this->assertEquals($rollArray, $exp);
        //
        //
        $rollSum = $controller->getDiceSum();
        $exp = 6 * 3 + 1 * 3;
        $this->assertEquals($rollSum, $exp);
    }
}
