<?php

declare(strict_types=1);

namespace App\Game;

/**
 * Class ComputerLogic.
 */
class ComputerLogic
{
    public function straight(array $lastRoll, array $combinations): array
    {
        $dicesToKeep = [];
        if (
            array_key_exists("Large Straight", $combinations) == false
            || array_key_exists("Small Straight", $combinations) == false
        ) {
            $largeStraight = [2, 3, 4, 5, 6];
            $smallStraight = [1, 2, 3, 4, 5];
            $matchL = 0;
            $matchS = 0;
            $dicesL = [];
            $dicesLValues = [];
            $dicesS = [];
            $dicesSValues = [];

            foreach ($lastRoll as $key => $dice) {
                if (in_array($dice, $largeStraight) && (in_array($dice, $dicesLValues) == false)) {
                    $matchL++;
                    $dicesL[] = $key;
                    $dicesLValues[] = $dice;
                }
                if (in_array($dice, $smallStraight) && (in_array($dice, $dicesSValues) == false)) {
                    $matchS++;
                    $dicesS[] = $key;
                    $dicesSValues[] = $dice;
                }
            }

            if ($matchL == 5 && array_key_exists("Large Straight", $combinations) == false) {
                $dicesToKeep = $dicesL;
            } elseif ($matchS == 5 && array_key_exists("Small Straight", $combinations) == false) {
                $dicesToKeep = $dicesS;
            } elseif ($matchL == 4 && array_key_exists("Large Straight", $combinations) == false) {
                $dicesToKeep = $dicesL;
            } elseif ($matchS == 4 && array_key_exists("Small Straight", $combinations) == false) {
                $dicesToKeep = $dicesS;
            }
        }

        return $dicesToKeep;
    }

    public function minTwoPair(array $arraySummary, array $lastRoll, array $numbers): array
    {
        $dicesToKeep = [];
        $len = count($numbers);
        for ($i = 0; $i < $len; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                if (
                    $arraySummary[$numbers[$i]] >= 2
                    && $arraySummary[$numbers[$j]] >= 2
                ) {
                    foreach ($lastRoll as $key => $dice) {
                        if ($dice == $numbers[$i] || $dice == $numbers[$j]) {
                            $dicesToKeep[] = $key;
                        }
                    }
                    return $dicesToKeep;
                }
            }
        }

        return $dicesToKeep;
    }

    public function pair(array $arraySummary, array $lastRoll, int $number): array
    {
        $dicesToKeep = [];

        if ($arraySummary[$number] >= 2) {
            foreach ($lastRoll as $key => $dice) {
                if ($dice == $number) {
                    $dicesToKeep[] = $key;
                }
            }

            return $dicesToKeep;
        }

        return $dicesToKeep;
    }

    public function oneNumber(array $arraySummary, array $lastRoll, int $number): array
    {
        $dicesToKeep = [];
        if ($arraySummary[$number] >= 3) {
            foreach ($lastRoll as $key => $dice) {
                if ($dice == $number) {
                    $dicesToKeep[] = $key;
                }
            }
                return $dicesToKeep;
        }

        return $dicesToKeep;
    }
}
