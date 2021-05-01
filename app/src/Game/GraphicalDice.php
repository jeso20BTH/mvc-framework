<?php

declare(strict_types=1);

namespace App\Game;

/**
 * Class GraphicalDice.
 */
class GraphicalDice extends Dice
{
    public function grapicalLastRoll(): array
    {
        switch ($this->getLastRoll()) {
            case 1:
                return [
                    [
                        "amount" => 1,
                        "spacing" => "center"
                    ],
                ];
            case 2:
                return [
                    [
                        "amount" => 1,
                        "spacing" => "start"
                    ],
                    [
                        "amount" => 1,
                        "spacing" => "end"
                    ]
                ];
            case 3:
                return [
                    [
                        "amount" => 1,
                        "spacing" => "start"
                    ],
                    [
                        "amount" => 1,
                        "spacing" => "center"
                    ],
                    [
                        "amount" => 1,
                        "spacing" => "end"
                    ]
                ];
            case 4:
                return [
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ],
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ]
                ];
            case 5:
                return [
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ],
                    [
                        "amount" => 1,
                        "spacing" => "center"
                    ],
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ]
                ];
            case 6:
                return [
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ],
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ],
                    [
                        "amount" => 2,
                        "spacing" => "between"
                    ]
                ];
            default:
                return [];
        }
    }
}
