<?php

declare(strict_types=1);

namespace App\Game;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Debug.
 */
class GameYatzyPointCheckerTest extends TestCase
{
    /**
     * Try to create the dicehand class.
     */
    public function testCreateThePlayerClass()
    {
        $controller = new YatzyPointChecker();
        $this->assertInstanceOf("\App\Game\YatzyPointChecker", $controller);
    }

    /**
     * Test upper method.
     */
    public function testUpper()
    {
        $controller = new YatzyPointChecker();
        $this->assertInstanceOf("\App\Game\YatzyPointChecker", $controller);

        $dices = [1, 1, 1, 4, 4];

        $target = 4;
        $sum = $controller->upper($dices, $target);
        $exp = 8;
        $this->assertEquals($exp, $sum);

        $target = 2;
        $sum = $controller->upper($dices, $target);
        $exp = 0;
        $this->assertEquals($exp, $sum);
    }

    /**
     * Test match method.
     */
    public function testMatch()
    {
        $controller = new YatzyPointChecker();
        $this->assertInstanceOf("\App\Game\YatzyPointChecker", $controller);

        $dices = [1, 1, 1, 4, 4];

        $target = 1;
        $minMatch = 4;
        $sum = $controller->match($dices, $target, $minMatch);
        $exp = 0;
        $this->assertEquals($exp, $sum);

        $target = 1;
        $minMatch = 3;
        $sum = $controller->match($dices, $target, $minMatch);
        $exp = 1;
        $this->assertEquals($exp, $sum);

        $target = 2;
        $minMatch = 4;
        $sum = $controller->match($dices, $target, $minMatch);
        $exp = 0;
        $this->assertEquals($exp, $sum);
    }

    /**
     * Test straight method.
     */
    public function testStraight()
    {
        $controller = new YatzyPointChecker();
        $this->assertInstanceOf("\App\Game\YatzyPointChecker", $controller);

        $dices = [1, 1, 1, 4, 4];
        $target = 5;
        $sum = $controller->straight($dices, $target);
        $exp = 0;
        $this->assertEquals($exp, $sum);

        $dices = [1, 2, 3, 4, 5];
        $target = 5;
        $sum = $controller->straight($dices, $target);
        $exp = 15;
        $this->assertEquals($exp, $sum);

        $dices = [6, 2, 3, 4, 5];
        $target = 6;
        $sum = $controller->straight($dices, $target);
        $exp = 20;
        $this->assertEquals($exp, $sum);
    }
}
