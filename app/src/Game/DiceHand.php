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
 * Class DiceHand.
 */
class DiceHand
{
    private array $dices;
    private ?int $sum = null;
    private string $output = "";
    private array $graphical;

    public function __construct(int $dices, int $diceFaces)
    {
        $this->dices = [];
        for ($i = 0; $i < $dices; $i++) {
            $this->dices[$i] = new GraphicalDice($diceFaces);
        }
    }

    public function roll(): void
    {
        $len = count($this->dices);

        $this->sum = 0;

        for ($i = 0; $i < $len; $i++) {
            $this->sum += $this->dices[$i]->roll();
        }
    }

    public function rollSpecific(array $dicesToRoll): void
    {
        $len = count($this->dices);
        $this->sum = 0;

        for ($i = 0; $i < $len; $i++) {
            if (in_array($i, $dicesToRoll)) {
                $this->sum += $this->dices[$i]->roll();
            } elseif (in_array($i, $dicesToRoll) == false) {
                $this->sum += $this->dices[$i]->getLastRoll();
            }
        }
    }

    public function getLastRoll(): string
    {
        $this->output = "";
        $len = count($this->dices);

        for ($i = 0; $i < $len; $i++) {
            $this->output .= strval($this->dices[$i]->getLastRoll()) . ", ";
        }

        return substr(strval($this->output), 0, -2);
    }

    public function getLastRollArray(): array
    {
        $roll = [];
        $len = count($this->dices);

        for ($i = 0; $i < $len; $i++) {
            if ($this->dices[$i]->getLastRoll()) {
                $roll[] = $this->dices[$i]->getLastRoll();
            }
        }

        return $roll;
    }

    public function getDiceSum(): ?int
    {
        return $this->sum;
    }

    public function getGraphicalRoll(): array
    {
        $len = count($this->dices);
        $this->graphical = [];

        for ($i = 0; $i < $len; $i++) {
            if ($this->dices[$i]->grapicalLastRoll() !== []) {
                array_push($this->graphical, $this->dices[$i]->grapicalLastRoll());
            }
        }
        return $this->graphical;
    }

    public function addDice(Dice $dice): void
    {
        $this->dices[] = $dice;
    }
}
