<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GamePlayerTest extends TestCase
{
    /**
     * Try to create the dicehand class.
     */
    public function testCreateThePlayerClass()
    {
        $controller = new Player("test", "player");
        $this->assertInstanceOf("\App\Game\Player", $controller);

        $type = $controller->getType();
        $exp = "player";
        $this->assertEquals($exp, $type);

        $player = $controller->presentPlayer();
        $exp = [
            "name" => "test",
            "combinations" => []
        ];
        $this->assertEquals($exp, $player);
    }

    /**
     * Get the dices to roll
     */
    public function testGetDicesToRoll()
    {
        $controller = new Player("test", "player");
        $this->assertInstanceOf("\App\Game\Player", $controller);

        $dicesToSave = [1, 3, 5];
        $dicesToRoll = $controller->getDicesToRoll($dicesToSave);
        $exp = [0, 2, 4];
        $this->assertEquals($exp, $dicesToRoll);
    }

    /**
     * Get the dices to roll
     */
    public function testDicesToRoll()
    {
        $controller = new Player("test", "player");
        $this->assertInstanceOf("\App\Game\Player", $controller);

        $dicesToRoll = $controller->dicesToRoll();
        $exp = [];
        $this->assertEquals($exp, $dicesToRoll);

        $controller->setDicesToRoll();
        $dicesToRoll = $controller->dicesToRoll();
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $dicesToRoll);


        $exp = [0, 2, 4];
        $controller->setDicesToRoll($exp);
        $dicesToRoll = $controller->dicesToRoll();
        $this->assertEquals($exp, $dicesToRoll);
    }

    /**
     * Get the dices to roll
     */
    public function testSetSum()
    {
        $controller = new Player("test", "player");
        $this->assertInstanceOf("\App\Game\Player", $controller);

        $controller->setSum("One pair", 12);
        $exp = ["One pair" => 12];
        $sum = $controller->getCombinations();
        $this->assertEquals($exp, $sum);
    }

    /**
     * Get the dices to roll
     */
    public function testSetScore()
    {
        $controller = new Player("test", "player");
        $this->assertInstanceOf("\App\Game\Player", $controller);

        $controller->setScore("One pair", 12);
        $controller->setScore("One pair", 12);
        $exp = ["One pair" => 12];
        $sum = $controller->getCombinations();
        $this->assertEquals($exp, $sum);
    }

    /**
     * Get the dices to roll
     */
    public function testRoll()
    {
        $controller = new Player("test", "player");
        $this->assertInstanceOf("\App\Game\Player", $controller);

        $controller->rollSpecific();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getLastRollArray')
             ->willReturn([6, 6]);

        $diceHand->method('getGraphicalRoll')
             ->willReturn([
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
        ]);

        $controller->setDiceHand($diceHand);

        $array = $controller->getLastRoll();
        $exp = [6, 6];
        $this->assertEquals($exp, $array);

        $graph = $controller->getGraphicalRoll();
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
                ]
            ];
        $this->assertEquals($exp, $graph);
    }
}
