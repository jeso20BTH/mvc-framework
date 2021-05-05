<?php

declare(strict_types=1);

namespace App\Game;

/**
 * Class YatzyGame.
 */
class YatzyGame
{
    private object $pointChecker;
    private string $type;
    private ?string $message;
    private ?int $turnCounter = 0;
    private ?int $rollCounter = 0;
    private array $players = [];
    private int $playerCounter = 0; // Keep track of who is the current player.
    const COMBINATIONS = [
        "upper" => [
            "Ones",
            "Twos",
            "Threes",
            "Fours",
            "Fives",
            "Sixes",
        ],
        "lower" => [
            "One Pair",
            "Two Pairs",
            "Three of a Kind",
            "Four of a Kind",
            "Small Straight",
            "Large Straight",
            "Full House",
            "Chance",
            "Yatzy"
        ]
    ];
    const ROLLS = 3;


    public function __construct()
    {
        $this->type = "menu";
        $this->pointChecker = new YatzyPointChecker();
    }

    public function addPlayer(string $type, string $name, ?object $test = null): void
    {
        if ($test) {
            $this->players[] = $test;
            return;
        }
        if ($type == "Player") {
            $this->players[] = new Player($name, $type);
        } elseif ($type == "Computer") {
            $this->players[] = new Computer($name, $type);
        }
    }

    public function startTurn(): void
    {
        $this->type = "roll";
        $this->rollCounter = 0;
        $this->setDicesToRoll();
        $this->roll();
    }

    public function roll(): void
    {
        $this->rollCounter++;

        if (count($this->players[$this->playerCounter]->dicesToRoll()) == 0) {
            $this->endOfRoll();
        }

        $this->players[$this->playerCounter]->rollSpecific();
        // Ha i slutet av metoden, efter ökande av tur.
        if ($this->rollCounter == self::ROLLS) {
            // Method for end of turn
            $this->endOfRoll();
            return;
        }
        if ($this->players[$this->playerCounter]->getType() == "Computer") {
            $this->computerBetweenRolls();
        }
    }

    private function computerBetweenRolls(): void
    {
        $dices = $this->players[$this->playerCounter]->keepLogic();
        $dices = $this->players[$this->playerCounter]->dicesToRoll();
        $this->players[$this->playerCounter]->setDicesToRoll($dices);

        $this->roll();
    }

    public function setDicesToRoll(array $dices = [0, 1, 2, 3, 4]): void
    {
        $this->players[$this->playerCounter]->setDicesToRoll($dices);
    }

    public function nextPlayer(array $dices = [0, 1, 2, 3, 4]): array
    {
        $this->players[$this->playerCounter]->setDicesToRoll($dices);

        return $dices;
    }


    public function getPlayers(): array
    {
        return $this->players;
    }

    private function endOfRoll(): void
    {
        $this->setDicesToRoll([]);

        if ($this->players[$this->playerCounter]->getType() == "Player") {
            $this->type = "place";
        }


        if ($this->players[$this->playerCounter]->getType() == "Computer") {
            $placement = $this->players[$this->playerCounter]->placeLogic();

            $placement = $placement["placement"];

            $this->endTurn($placement);
        }
    }

    public function scoreHandler(string $placement, ?array $dices = null): void
    {
        $lastRoll = $dices ?? $this->players[$this->playerCounter]->getLastRoll();
        $sum = 0;

        $upper = [
            "Ones" => 1,
            "Twos" => 2,
            "Threes" => 3,
            "Fours" => 4,
            "Fives" => 5,
            "Sixes" => 6
        ];
        switch ($placement) {
            case 'Ones':
            case 'Twos':
            case 'Threes':
            case 'Fours':
            case 'Fives':
            case 'Sixes':
                $sum = $this->pointChecker->upper($lastRoll, $upper[$placement]);
                break;
            case 'One Pair':
                $sum = $this->pointChecker->match($lastRoll, 6, 2) * 2;
                break;
            case 'Two Pairs':
                $pairOne = $this->pointChecker->match($lastRoll, 6, 2);
                $pairTwo = $this->pointChecker->match($lastRoll, $pairOne - 1, 2);

                $sum = $pairOne * 2 + $pairTwo * 2;

                if ($pairOne == 0 || $pairTwo == 0) {
                    $sum = 0;
                }
                break;
            case 'Three of a Kind':
                $sum = $this->pointChecker->match($lastRoll, 6, 3) * 3;
                break;
            case 'Four of a Kind':
                $sum = $this->pointChecker->match($lastRoll, 6, 4) * 4;
                break;
            case 'Small Straight':
                $sum = $this->pointChecker->straight($lastRoll, 5);
                break;
            case 'Large Straight':
                $sum = $this->pointChecker->straight($lastRoll, 6);
                break;
            case 'Full House':
                $three = $this->pointChecker->match($lastRoll, 6, 3);
                $two = $this->pointChecker->match($lastRoll, 6, 2);

                if ($three == $two) {
                    $two = $this->pointChecker->match($lastRoll, $three - 1, 2);
                }

                $sum = $three * 3 + $two * 2;

                if ($three == 0 || $two == 0) {
                    $sum = 0;
                }
                break;
            case 'Chance':
                $sum = array_sum($lastRoll) ;
                break;
            case 'Yatzy':
                $sum = $this->pointChecker->match($lastRoll, 6, 5);

                if ($sum != 0) {
                    $sum = 50;
                }
                break;
        }

        $this->players[$this->playerCounter]->setScore($placement, $sum);
        $this->setSums();
    }

    public function setSums(): void
    {
        $upperSum = 0;
        $lowerScore = 0;
        $bonus = 0;

        foreach ($this->players[$this->playerCounter]->getCombinations() as $combination => $value) {
            if (in_array($combination, self::COMBINATIONS["upper"])) {
                $upperSum += $value;
            } elseif (in_array($combination, self::COMBINATIONS["lower"])) {
                $lowerScore += $value;
            }
        }

        if ($upperSum >= 63) {
            $bonus = 50;
        }

        $upperScore = $upperSum + $bonus;
        $totalScore = $upperScore + $lowerScore;

        $this->players[$this->playerCounter]->setSum("upper_sum", $upperSum);
        $this->players[$this->playerCounter]->setSum("bonus", $bonus);
        $this->players[$this->playerCounter]->setSum("upper_score", $upperScore);
        $this->players[$this->playerCounter]->setSum("lower_score", $lowerScore);
        $this->players[$this->playerCounter]->setSum("total_score", $totalScore);
    }

    public function endTurn(string $placement): void
    {
        $this->scoreHandler($placement);

        $this->playerCounter++;

        if ($this->playerCounter > count($this->players) - 1) {
            $this->turnCounter ++;
        }

        if ($this->playerCounter >= count($this->players)) {
            $this->playerCounter = 0;
        }

        if ($this->turnCounter >= count(self::COMBINATIONS["upper"]) + count(self::COMBINATIONS["lower"])) {
            $this->type = "summary";
            // echo "<pre>";
            // var_dump($this->players);
            // echo "<pre>";
            return;
        }



        $this->startTurn();
    }

    public function getDicesToRoll(array $dices)
    {
        return $this->players[$this->playerCounter]->getDicesToRoll($dices);
    }

    public function postController(): array
    {
        $action = $_POST["action"];
        if ($action == "Start game") {
            $this->startTurn();



            // $this->start($dices);
        } elseif ($action == "Keep") {
            $dices = $_POST["dices"] ?? [];
            $dicesToRoll = $this->players[$this->playerCounter]->getDicesToRoll($dices);
            $this->setDicesToRoll($dicesToRoll);
            $this->roll();
        } elseif ($action == "New player") {
            $this->type = "add";
        } elseif ($action == "Add player") {
            $this->type = "menu";
            $this->addPlayer($_POST["type"], $_POST["name"]);
        } elseif ($action == "Menu") {
            $this->type = "menu";
            $this->players = [];
            $this->turnCounter = 0;
            $this->playerCounter = 0;
        } elseif ($action == "Place") {
            $this->endTurn($_POST["placement"]);
        }

        return $this->renderGame();
    }
    //
    // public function clearData(): void
    // {
    //     unset($_SESSION["standings"]);
    //     $this->standings = array(
    //         "player" => 0,
    //         "computer" => 0
    //     );
    //
    //     $this->playerMoney = 10;
    //     $this->computerMoney = 100;
    // }
    //
    public function getLastGraphicRoll(): array
    {
        if (count($this->players) != 0) {
            return $this->players[$this->playerCounter]->getGraphicalRoll();
        }
        return [];
    }

    public function presentPlayers(): array
    {
        $pla = [];

        foreach ($this->players as $player) {
            $pla[] = $player->presentPlayer();
        }
        return $pla;
    }

    public function setHighscores(): array
    {
        $scores = [];

        foreach ($this->players as $player) {
            $scores[] = [
                'name' => $player->getName(),
                'score' => $player->getCombinations()["total_score"]
            ];
        }
        return $scores;
    }

    public function getType(): string
    {
        return $this->type;
    }
    //
    public function renderGame(): array
    {
        $data = [
            "header" => "Lets play Yatzy!",
            "combinations" => self::COMBINATIONS,
            // "standings" => $this->getStandings(),
            "type" => $this->type,
            "players" => $this->presentPlayers(),
            // "lastRoll" => $this->getLastRoll() ?? null,
            // "roller" => $this->roller ?? null,
            "message" => $this->message ?? null,
            "title" => "Yatzy",
            "graphic" => $this->getLastGraphicRoll() ?? null,
            "playerCounter" => $this->playerCounter
        ];

        $this->message = null;

        return $data;
    }
}
