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
class YatzyPointChecker
{
    public function upper(array $dices, int $target): int
    {
        if (in_array($target, $dices)) {
            return array_count_values($dices)[$target] * $target;
        }
        return 0;
    }

    public function match(array $dices, int $start, int $minimumMatch): int
    {
        for ($i = $start; $i > 0; $i--) {
            if (in_array($i, $dices)) {
                if (array_count_values($dices)[$i] >= $minimumMatch) {
                    return  $i;
                }
            }
        }
        return 0;
    }

    public function straight(array $dices, int $start): int
    {
        $sum = 0;
        $len = count($dices);
        for ($i = $start; $i > $start - $len; $i--) {
            if ($this->upper($dices, $i) == 0) {
                return  0;
            }
            $sum += $i;
        }
        return $sum;
    }
}
