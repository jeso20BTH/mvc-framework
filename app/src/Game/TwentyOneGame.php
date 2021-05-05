<?php

declare(strict_types=1);

namespace App\Game;

/**
 * Class TO.
 */
class TwentyOneGame
{
    private int $playerSum = 0;
    private int $computerSum = 0;
    private ?string $playerName = null;
    private object $diceHand;
    private array $standings;
    private string $type;
    private string $roller;
    private int $maxPlayerMoney = 10;
    private int $playerMoney = 10;
    private int $computerMoney = 100;
    private ?int $currentBet = 0;
    private ?string $message = null;
    private object $highscoreHandler;



    public function __construct()
    {
        $this->diceHand = new DiceHand(2, 6);
        $this->roller = "player";

        $this->playerMoney = 10;
        $this->computerMoney = 100;
        $this->standings = array(
            "player" => 0,
            "computer" => 0
        );
        $this->type = "menu";
    }

    public function setPlayer(string $name) {
        $this->playerName = $name;
    }

    public function clearBet() {
        $this->currentBet = null;
    }

    public function endRoll(string $type): string
    {
        if ($type == "player") {
            if ($this->playerSum > 21) {
                $this->message = "Busted! <br> Computer wins!";
                return "over";
            } elseif ($this->playerSum == 21) {
                $this->message = "You got 21! <br> Congratulation!!!";
                return "21";
            }

            return "roll";
        }
        if ($this->computerSum > 21) {
            return "over";
        } elseif ($this->computerSum == 21) {
            return "21";
        } elseif ($this->computerSum >= 17) {
            return "stop";
        }

        return "roll";
    }

    private function endGame(string $winner): void
    {
        $this->type = "end";
        if ($winner == "player") {
            $this->standings["player"] += 1;
            $this->playerMoney += $this->currentBet;
            $this->computerMoney -= $this->currentBet;
            if ($this->message == null) {
                $this->message = "Congratulation you won!!!";
            }

            if ($this->playerMoney > $this->maxPlayerMoney) {
                $this->maxPlayerMoney = $this->playerMoney;
            }

            return;
        } elseif ($winner == "computer") {
            $this->standings["computer"] += 1;
            $this->playerMoney -= $this->currentBet;
            $this->computerMoney += $this->currentBet;

            if ($this->message == null) {
                $this->message = "Computer won!";
            }
        }

        if ($this->playerMoney <= 0) {
            $this->type = "game over";
            $this->message = "Computer won! Game over";
        }

        $_SESSION["standings"] = $this->standings;
        $this->currentBet = null;
    }

    public function changeRoller(string $newRoller) {
        $this->roller = $newRoller;
    }

    public function roll(): void
    {
        $type = $this->roller;
        $this->diceHand->roll();

        if ($type == "player") {
            $this->playerSum += $this->diceHand->getDiceSum();

            $result = $this->endRoll("player");
            if ($result == "over") {
                $this->endGame("computer");
            } elseif ($result == "21") {
                $this->endGame("player");
            }
            return;
        }

        $this->computerSum += $this->diceHand->getDiceSum();

        $result = $this->endRoll("computer");
        if ($result == "over") {
            $this->endGame("player");
            return;
        } elseif ($result == "21") {
            $this->endGame("computer");
            return;
        } elseif ($result == "stop") {
            if ($this->playerSum > $this->computerSum) {
                $this->endGame("player");
                return;
            }
            $this->endGame("computer");
            return;
        }
        $this->roll();
        return;
    }

    public function start(int $dices, int $bet, DiceHand $diceHand = null): void
    {
        $this->diceHand = new DiceHand($dices, 6);

        if ($diceHand) {
            $this->diceHand = $diceHand;
        }

        $this->type = "play";
        $this->playerSum = 0;
        $this->computerSum = 0;
        $this->currentBet = $bet;

        $this->roller = "player";
    }



    public function getType(): string
    {
        return $this->type;
    }

    public function getSum(string $type): int
    {
        if ($type == "player") {
            return $this->playerSum;
        }
        return $this->computerSum;
    }

    public function getLastRoll(): string
    {
        return $this->diceHand->getLastRoll();
    }

    public function getStandings(): array
    {
        return $this->standings;
    }

    public function postController(): array
    {
        $action = $_POST["action"];
        if ($action == "Start game") {
            $dices = $_POST["dices"] ?? "1";
            $dices = intval($dices);
            $this->currentBet = intval($_POST["bet"]);
            if ($this->playerName === null) {
                $this->playerName = $_POST["name"];
            }
            $this->start($dices);
        } elseif ($action == "Clear data") {
            $this->clearData();
        } elseif ($action == "Roll") {
            $this->roll($this->roller);
        } elseif ($action == "Stop") {
            $this->roller = "computer";
            $this->roll($this->roller);
        } elseif ($action == "Menu") {
            $this->type = "menu";
        }

        return $this->renderGame();
    }

    public function clearData(): void
    {
        var_dump("Clear");
        unset($_SESSION["standings"]);
        $this->standings = array(
            "player" => 0,
            "computer" => 0
        );

        $this->playerMoney = 10;
        $this->computerMoney = 100;
    }

    public function getLastGraphicRoll(): array
    {
        return $this->diceHand->getGraphicalRoll();
    }

    public function renderGame(): array
    {
        $data = [
            "playerName" => $this->playerName ?? null,
            "maxPlayerMoney" => $this->maxPlayerMoney ?? null,
            "header" => "Lets play 21!",
            "standings" => $this->getStandings(),
            "type" => $this->type,
            "playerSum" => $this->playerSum ?? null,
            "lastRoll" => $this->getLastRoll() ?? null,
            "computerSum" => $this->computerSum ?? null,
            "roller" => $this->roller ?? null,
            "playerMoney" => $this->playerMoney ?? null,
            "computerMoney" => $this->computerMoney ?? null,
            "currentBet" => $this->currentBet ?? null,
            "message" => $this->message ?? null,
            "title" => "21",
            "graphic" => $this->getLastGraphicRoll() ?? null
        ];

        $this->message = null;

        return $data;
    }

    public function setSum(string $type, int $sum): void
    {
        if ($type == "player") {
            $this->playerSum = $sum;
            return;
        }
        $this->computerSum = $sum;
    }

    public function setDiceHand(DiceHand $diceHand): void
    {
        $this->diceHand = $diceHand;
    }
}
