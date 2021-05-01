<?php

declare(strict_types=1);

namespace App\Game;

use function Mos\Functions\{
    destroySession,
    renderView,
    renderTwigView,
    url
};

/**
 * Class Dice.
 */
class Dice
{
    private int $faces;
    private ?int $roll = null;

    public function __construct(int $faces)
    {
        $this->faces = $faces;
    }

    public function roll(): int
    {
        $this->roll = rand(1, $this->faces);

        return $this->roll;
    }

    public function getLastRoll(): ?int
    {
        return $this->roll;
    }

    public function setLastRoll(int $roll): void
    {
        $this->roll = $roll;
    }
}
