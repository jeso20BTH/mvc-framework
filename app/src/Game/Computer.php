<?php

declare(strict_types=1);

namespace Jeso20\Game;

use function Mos\Functions\{
    destroySession,
    renderView,
    renderTwigView,
    url
};

/**
 * Class TO.
 */
class Computer extends Player
{
    private object $logic;
    private object $pointChecker;

    const UPPER = [
        1 => "Ones",
        2 => "Twos",
        3 => "Threes",
        4 => "Fours",
        5 => "Fives",
        6 => "Sixes"
    ];
    const PRIORITYORDER = [
        "Yatzy",
        "Full House",
        "Large Straight",
        "Small Straight",
        "Sixes>=3",
        "Fives>=3",
        "Fours>=3",
        "Threes>=3",
        "Twos>=3",
        "Ones>=3",
        "Four of a Kind",
        "Three of a Kind",
        "Two Pairs",
        "One Pair",
        "Ones",
        "Twos",
        "Threes",
        "Fours",
        "Fives",
        "Sixes",
        "Chance",
    ];
    const SKIPORDER = [
        "Small Straight",
        "Large Straight",
        "Four of a Kind",
        "Full House",
        "Three of a Kind",
        "Two Pairs",
        "One Pair",
        "Sixes",
        "Fives",
        "Fours",
        "Threes",
        "Twos",
        "Ones",
        "Yatzy",
        "Chance",
    ];
    const UPPER_PLACE = [
        "Ones" => 1,
        "Twos" => 2,
        "Threes" => 3,
        "Fours" => 4,
        "Fives" => 5,
        "Sixes" => 6,
        'Ones>=3' => 1,
        'Twos>=3' => 2,
        'Threes>=3' => 3,
        'Fours>=3' => 4,
        'Fives>=3' => 5,
        'Sixes>=3' => 6
    ];

    public function keepLogic(): array
    {
        $this->logic = new ComputerLogic();

        $dicesToKeep = [];
        $arraySummary = array_count_values($this->getLastRoll());
        $rolls = $this->getRolls();

        if (count($arraySummary) >= 4) {
            $dicesToRoll = $this->logic->straight($this->getLastRoll(), $this->combinations);

            if (count($dicesToRoll) > 0) {
                return $dicesToRoll;
            }
        }

        if (count($arraySummary) <= 3) {
            // Check for full house
            if (array_key_exists("Full house", $this->combinations) == false) {
                $dicesToRoll = $this->logic->minTwoPair($arraySummary, $this->getLastRoll(), $rolls);

                if (count($dicesToRoll) > 0) {
                    return $dicesToRoll;
                }
            }

            // Check if Yatzy is not taken
            if (array_key_exists("Yatzy", $this->combinations) == false) {
                foreach ($rolls as $number) {
                    $dicesToRoll = $this->logic->oneNumber($arraySummary, $this->getLastRoll(), $number);

                    if (count($dicesToRoll) > 3) {
                        return $dicesToRoll;
                    }
                }
            }

            // Check if upper[$highestNumber] not taken
            foreach ($rolls as $number) {
                if (in_array(self::UPPER[$number], $this->combinations) == false) {
                    $dicesToRoll = $this->logic->oneNumber($arraySummary, $this->getLastRoll(), $number);

                    if (count($dicesToRoll) > 0) {
                        return $dicesToRoll;
                    }
                }
            }

            // Check for Four of kind or Three of kind
            if (
                array_key_exists("Three of a Kind", $this->combinations) == false
                || array_key_exists("Four of a Kind", $this->combinations) == false
            ) {
                foreach ($rolls as $number) {
                    $dicesToRoll = $this->logic->oneNumber($arraySummary, $this->getLastRoll(), $number);

                    if (count($dicesToRoll) > 0) {
                        return $dicesToRoll;
                    }
                }
            }

            // Check for two pair
            if (array_key_exists("Two Pairs", $this->combinations) == false) {
                $dicesToRoll = $this->logic->minTwoPair($arraySummary, $this->getLastRoll(), $rolls);

                if (count($dicesToRoll) > 0) {
                    return $dicesToRoll;
                }
            }
        }

        // Check for pair
        if (array_key_exists("One Pair", $this->combinations) == false) {
            foreach ($rolls as $number) {
                $dicesToRoll = $this->logic->pair($arraySummary, $this->getLastRoll(), $number);

                if (count($dicesToRoll) > 0) {
                    return $dicesToRoll;
                }
            }
        }
        return $dicesToKeep;
    }

    private function getRolls(): array
    {
        $rolls = [];

        foreach ($this->getLastRoll() as $dice) {
            if ($rolls == []) {
                $rolls[] = $dice;
            }

            foreach ($rolls as $key => $roll) {
                if ($dice > $roll && in_array($dice, $rolls) == false) {
                    $tempRolls = array_slice($rolls, 0, $key);
                    $tempRolls[] = $dice;
                    $rolls = array_merge($tempRolls, array_slice($rolls, $key));
                } elseif (in_array($dice, $rolls) == false) {
                    $rolls[] = $dice;
                }
            }
        }
        return $rolls;
    }

    public function place(string $placement): array
    {
        $this->pointChecker = new YatzyPointChecker();
        $sum = 0;
        $placeArray = [];

        switch ($placement) {
            case 'Ones':
            case 'Twos':
            case 'Threes':
            case 'Fours':
            case 'Fives':
            case 'Sixes':
                $sum = $this->pointChecker->upper($this->getLastRoll(), self::UPPER_PLACE[$placement]);

                break;
            case 'Ones>=3':
            case 'Twos>=3':
            case 'Threes>=3':
            case 'Fours>=3':
            case 'Fives>=3':
            case 'Sixes>=3':
                $sum = $this->pointChecker->upper($this->getLastRoll(), self::UPPER_PLACE[$placement]);

                if ($sum < self::UPPER_PLACE[$placement] * 3) {
                    $sum = 0;
                }
                $placement = substr("$placement", 0, -3);
                break;
            case 'One Pair':
                $sum = $this->pointChecker->match($this->getLastRoll(), 6, 2) * 2;
                break;
            case 'Two Pairs':
                $pairOne = $this->pointChecker->match($this->getLastRoll(), 6, 2);
                $pairTwo = $this->pointChecker->match($this->getLastRoll(), $pairOne - 1, 2);

                $sum = $pairOne * 2 + $pairTwo * 2;

                if ($pairOne != 0 || $pairTwo != 0) {
                    $sum = 0;
                }
                break;
            case 'Three of a Kind':
                $sum = $this->pointChecker->match($this->getLastRoll(), 6, 3) * 3;
                break;
            case 'Four of a Kind':
                $sum = $this->pointChecker->match($this->getLastRoll(), 6, 4) * 4;
                break;
            case 'Small Straight':
                $sum = $this->pointChecker->straight($this->getLastRoll(), 5);
                break;
            case 'Large Straight':
                $sum = $this->pointChecker->straight($this->getLastRoll(), 6);
                break;
            case 'Full House':
                $three = $this->pointChecker->match($this->getLastRoll(), 6, 3);
                $two = $this->pointChecker->match($this->getLastRoll(), 6, 2);

                if ($three == $two) {
                    $two = $this->pointChecker->match($this->getLastRoll(), $three - 1, 2);
                }

                $sum = $three * 3 + $two * 2;

                if ($three == 0 || $two == 0) {
                    $sum = 0;
                }
                break;
            case 'Chance':
                $sum = array_sum($this->getLastRoll());
                break;
            case 'Yatzy':
                $sum = $this->pointChecker->match($this->getLastRoll(), 6, 5);

                if ($sum != 0) {
                    $sum = 50;
                }
                break;
        }

        if ($sum > 0) {
            $placeArray = [
                "placement" => $placement,
                "sum" => $sum
            ];
        }

        return $placeArray;
    }

    public function placeLogic(): array
    {
        $placement = [];
        $placementCounter = 0;
        $skipCounter = 0;
        $placeStop = false;
        while ($placement == []) {
            $sum = 0;

            if ($placementCounter < count(self::PRIORITYORDER)) {
                if (array_key_exists(self::PRIORITYORDER[$placementCounter], $this->combinations) == false) {
                    $placement = $this->place(self::PRIORITYORDER[$placementCounter]);
                }
                $placementCounter++;
            }

            if ($placementCounter >= count(self::PRIORITYORDER)) {
                $placeStop = true;
            }
            while ($placement == [] && $placeStop) {
                $sum = 0;
                if (array_key_exists(self::SKIPORDER[$skipCounter], $this->combinations) == false) {
                    $placement = [
                                    "placement" => self::SKIPORDER[$skipCounter],
                                    "sum" => $sum
                                ];
                }

                $skipCounter++;

                if ($skipCounter >= count(self::SKIPORDER)) {
                    break;
                }
            }

            if ($skipCounter >= count(self::SKIPORDER)) {
                break;
            }
        }

        return $placement;
    }
}
