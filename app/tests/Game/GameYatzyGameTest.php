<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameYatzyGameTest extends TestCase
{
    /**
     * Try to create the dicehand class.
     */
    public function testCreateTheYatzyGameClass()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testAddPlayer()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");
        $controller->addPlayer("Computer", "TestC");

        $players = $controller->presentPlayers();
        $exp = [
            ["name" => "TestP", "combinations" => []],
            ["name" => "TestC", "combinations" => []]
        ];
        $this->assertEquals($exp, $players);

        $players = $controller->getPlayers();
        $this->assertInstanceOf("\App\Game\Player", $players[0]);
        $this->assertInstanceOf("\App\Game\Computer", $players[1]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerUpper()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Sixes", [6, 6, 6, 6, 6]);

        $players = $controller->presentPlayers();
        $exp = 30;
        $this->assertEquals($exp, $players[0]["combinations"]["Sixes"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerPair()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("One Pair", [6, 6, 6, 6, 6]);

        $players = $controller->presentPlayers();
        $exp = 12;
        $this->assertEquals($exp, $players[0]["combinations"]["One Pair"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerTwoPairMatch()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Two Pairs", [6, 6, 5, 5, 6]);

        $players = $controller->presentPlayers();
        $exp = 22;
        $this->assertEquals($exp, $players[0]["combinations"]["Two Pairs"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerTwoPairNoMatch()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Two Pairs", [6, 6, 4, 5, 6]);

        $players = $controller->presentPlayers();
        $exp = 0;
        $this->assertEquals($exp, $players[0]["combinations"]["Two Pairs"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerThreeOfAKind()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Three of a Kind", [6, 6, 4, 5, 6]);

        $players = $controller->presentPlayers();
        $exp = 18;
        $this->assertEquals($exp, $players[0]["combinations"]["Three of a Kind"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerFourOfAKind()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Four of a Kind", [6, 6, 4, 6, 6]);

        $players = $controller->presentPlayers();
        $exp = 24;
        $this->assertEquals($exp, $players[0]["combinations"]["Four of a Kind"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerSmallStraight()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Small Straight", [1, 2, 3, 4, 5]);

        $players = $controller->presentPlayers();
        $exp = 15;
        $this->assertEquals($exp, $players[0]["combinations"]["Small Straight"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerLargeStraight()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Large Straight", [6, 2, 3, 4, 5]);

        $players = $controller->presentPlayers();
        $exp = 20;
        $this->assertEquals($exp, $players[0]["combinations"]["Large Straight"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerFullHouseMatch()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Full House", [6, 6, 6, 5, 5]);

        $players = $controller->presentPlayers();
        $exp = 28;
        $this->assertEquals($exp, $players[0]["combinations"]["Full House"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerFullHouseNoMatch()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Full House", [6, 6, 6, 6, 5]);

        $players = $controller->presentPlayers();
        $exp = 0;
        $this->assertEquals($exp, $players[0]["combinations"]["Full House"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerChance()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Chance", [6, 6, 6, 6, 5]);

        $players = $controller->presentPlayers();
        $exp = 29;
        $this->assertEquals($exp, $players[0]["combinations"]["Chance"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testScoreHandlerYatzy()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Yatzy", [6, 6, 6, 6, 6]);

        $players = $controller->presentPlayers();
        $exp = 50;
        $this->assertEquals($exp, $players[0]["combinations"]["Yatzy"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testsetSumsBonus()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->scoreHandler("Sixes", [6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6]);

        $players = $controller->presentPlayers();
        $exp = 50;
        $this->assertEquals($exp, $players[0]["combinations"]["bonus"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testSetDicesToRoll()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $controller->addPlayer("Player", "TestP");

        $controller->setDicesToRoll([0,2,4]);

        $players = $controller->getPlayers();
        $dices = ($players[0]->dicesToRoll());
        $exp = [0,2,4];
        $this->assertEquals($exp, $dices);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testGetLastGraphicRoll()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $player = $this->createStub(DiceHand::class);

        $player->method('getGraphicalRoll')
             ->willReturn([
                 [
                     "amount" => 1,
                     "spacing" => "center"
                 ],
             ]);

        $controller->addPlayer("Player", "TestP", $player);

        $graph = $controller->getLastGraphicRoll();
        $exp = [
            [
                "amount" => 1,
                "spacing" => "center"
            ],
        ];
        $this->assertEquals($exp, $graph);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testNextPlayerRoll()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);


        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $controller->roll();

        $players = $controller->presentPlayers();
        $this->assertTrue(count($players[0]["combinations"]) > 0);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testNextPlayer()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);


        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $nextPlayer = $controller->nextPlayer();
        $exp = [0, 1, 2, 3, 4];
        $this->assertEquals($exp, $nextPlayer);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testComputer()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);


        $controller->addPlayer("Computer", "TestC");

        $controller->roll();

        $players = $controller->presentPlayers();
        $this->assertTrue(count($players[0]["combinations"]) > 0);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerStart()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $_POST["action"] = "Start game";

        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $controller->postController();

        $players = $controller->presentPlayers();
        $this->assertTrue(count($players[0]["combinations"]) > 0);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerKeep()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $_POST["action"] = "Keep";

        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $res = $controller->postController();

        $exp = "roll";
        $this->assertEquals($exp, $res["type"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerNewPlayer()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $_POST["action"] = "New player";

        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $res = $controller->postController();

        $exp = "add";
        $this->assertEquals($exp, $res["type"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerAddPlayer()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $_POST["action"] = "Add player";
        $_POST["type"] = "Player";
        $_POST["name"] = "Apa";

        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $res = $controller->postController();

        $exp = "menu";
        $this->assertEquals($exp, $res["type"]);

        $players = $controller->presentPlayers();
        $exp = "Apa";
        $this->assertEquals($exp, $players[2]["name"]);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerMenu()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $_POST["action"] = "Menu";

        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $res = $controller->postController();

        $exp = "menu";
        $this->assertEquals($exp, $res["type"]);

        $players = $controller->presentPlayers();
        $exp = [];
        $this->assertEquals($exp, $players);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerPlace()
    {
        $controller = new YatzyGame();
        $this->assertInstanceOf("\App\Game\YatzyGame", $controller);

        $_POST["action"] = "Place";
        $_POST["placement"] = "One Pair";

        $controller->addPlayer("Computer", "TestC");
        $controller->addPlayer("Player", "TestP");

        $res = $controller->postController();

        $exp = "roll";
        $this->assertEquals($exp, $res["type"]);
    }
}
