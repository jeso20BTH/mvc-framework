<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameTwentyOneGameTest extends TestCase
{
    /**
     * Try to create the dicehand class.
     */
    public function testCreateTheTwentyOneGameClass()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testSetSum()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);

        $playerSum = 13;
        $computerSum = 14;

        $controller->setSum("player", $playerSum);
        $controller->setSum("computer", $computerSum);

        $player = $controller->getSum("player");
        $computer = $controller->getSum("computer");

        $this->assertEquals($playerSum, $player);
        $this->assertEquals($computerSum, $computer);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testEndRoll()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);

        $playerSum = 22;
        $controller->setSum("player", $playerSum);
        $player = $controller->endRoll("player");
        $exp = "over";
        $this->assertEquals($exp, $player);

        $playerSum = 21;
        $controller->setSum("player", $playerSum);
        $player = $controller->endRoll("player");
        $exp = "21";
        $this->assertEquals($exp, $player);

        $playerSum = 20;
        $controller->setSum("player", $playerSum);
        $player = $controller->endRoll("player");
        $exp = "roll";
        $this->assertEquals($exp, $player);

        $computerSum = 22;
        $controller->setSum("computer", $computerSum);
        $computer = $controller->endRoll("computer");
        $exp = "over";
        $this->assertEquals($exp, $computer);

        $computerSum = 21;
        $controller->setSum("computer", $computerSum);
        $computer = $controller->endRoll("computer");
        $exp = "21";
        $this->assertEquals($exp, $computer);

        $computerSum = 17;
        $controller->setSum("computer", $computerSum);
        $computer = $controller->endRoll("computer");
        $exp = "stop";
        $this->assertEquals($exp, $computer);

        $computerSum = 16;
        $controller->setSum("computer", $computerSum);
        $computer = $controller->endRoll("computer");
        $exp = "roll";
        $this->assertEquals($exp, $computer);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testStartGame()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(18);

        $res = $controller->start(2, $diceHand);
        $this->assertEmpty($res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollPlayerBusted()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(22);

        $controller->setDiceHand($diceHand);

        $controller->roll("player");
        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 1];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollPlayer21()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);

        $controller->clearData();
        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(21);

        $controller->setDiceHand($diceHand);

        $controller->roll("player");
        $res = $controller->getStandings();
        $exp = ["player" => 1, "computer" => 0];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollComputerBusted()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(22);

        $controller->setDiceHand($diceHand);

        $controller->roll("computer");
        $res = $controller->getStandings();
        $exp = ["player" => 1, "computer" => 0];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollComputer21()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(21);

        $controller->setDiceHand($diceHand);

        $controller->roll("computer");
        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 1];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollPlayerHigher()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(20);

        $controller->setDiceHand($diceHand);

        $controller->roll("player");

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(18);

        $controller->setDiceHand($diceHand);

        $controller->roll("computer");
        $res = $controller->getStandings();
        $exp = ["player" => 1, "computer" => 0];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollComputerHigher()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(17);

        $controller->setDiceHand($diceHand);

        $controller->roll("player");

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(18);

        $controller->setDiceHand($diceHand);

        $controller->roll("computer");
        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 1];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testRollComputerRollAgain()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(7);

        $controller->setDiceHand($diceHand);

        $controller->roll("computer");
        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 1];
        $this->assertEquals($exp, $res);

        $sum = $controller->getSum("computer");
        $exp = 21;
        $this->assertEquals($exp, $sum);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerStart()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $_POST["action"] = "Start game";
        $_POST["bet"] = "5";

        $controller->postController();

        $sum1 = $controller->getSum("player");
        $this->assertTrue($sum1 > 0);

        $_POST["action"] = "Roll";

        $controller->postController();

        $sum2 = $controller->getSum("player");
        $this->assertTrue($sum2 > $sum1);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerClear()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(21);

        $controller->setDiceHand($diceHand);

        $controller->roll("computer");
        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 1];

        $this->assertEquals($exp, $res);

        $_POST["action"] = "Clear data";
        $controller->postController();

        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 0];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerStop()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $diceHand = $this->createStub(DiceHand::class);

        $diceHand->method('getDiceSum')
             ->willReturn(21);

        $controller->setDiceHand($diceHand);

        $_POST["action"] = "Stop";
        $controller->postController();

        $res = $controller->getStandings();
        $exp = ["player" => 0, "computer" => 1];

        $this->assertEquals($exp, $res);
    }

    /**
     * Try to create the dicehand class.
     */
    public function testPostControllerMenu()
    {
        $controller = new TwentyOneGame();
        $this->assertInstanceOf("\App\Game\TwentyOneGame", $controller);
        $controller->clearData();

        $_POST["action"] = "Menu";

        $controller->postController();

        $type = $controller->getType();
        $exp = "menu";
        $this->assertEquals($exp, $type);
    }
}
