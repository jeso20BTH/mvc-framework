<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Jeso20\Game\TwentyOneGame;
use Jeso20\Game\YatzyGame;

class GameController extends AbstractController
{
    private object $session;

    public function __construct()
    {
        // $this->session = new Session();
        // $this->session->start();
    }

    /**
     * @Route("/", name="index")
    */
    public function greeting(): Response
    {
        return $this->render('standard.html.twig', [
            'header' => "Welcome!",
            'message' => 'This is my page!'
        ]);
    }

    /**
     * @Route(
     *      "/twentyone",
     *      name="twentyone",
     *      methods={"GET"}
     * )
     *
    */
    public function twentyOne(): Response
    {
        var_dump($this->session);
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $data = $callable->renderGame();
        $this->session->set("TwentyOne", $callable);

        var_dump($this->session);

        return $this->render('twentyone.html.twig', [
            'title' => $data["title"] ?? null,
            'header' => $data["header"] ?? null,
            'playerVictories' => $data["standings"]["player"] ?? null,
            'computerVictories' => $data["standings"]["computer"] ?? null,
            'type' => $data["type"] ?? null,
            'playerSum' => $data["playerSum"] ?? 0,
            'computerSum' => $data["computerSum"] ?? 0,
            'lastRoll' => $data["lastRoll"] ?? null,
            'roller' => $data["roller"] ?? null,
            'playerMoney' => $data["playerMoney"] ?? 0,
            'computerMoney' => $data["computerMoney"] ?? 0,
            'currentBet' => $data["currentBet"] ?? 0,
            'message' => $data["message"] ?? null,
            'graphic' => $data["graphic"] ?? null
        ]);
    }

    /**
    * @Route(
    *      "/twentyone",
    *      name="twentyonepost",
    *      methods={"POST"}
    * )
    */
    public function twentyOnePost(): Response
    {
        // $header = $header ?? null;
        // $message = $message ?? null;
        // $playerVictories = $standings["player"] ?? null;
        // $computerVictories = $standings["computer"] ?? null;
        // $type = $type ?? null;
        // $playerSum = $playerSum ?? 0;
        // $computerSum = $computerSum ?? 0;
        // $lastRoll = $lastRoll ?? null;
        // $roller = $roller ?? null;
        // $playerMoney = $playerMoney ?? 0;
        // $computerMoney = $computerMoney ?? 0;
        // $currentBet = $currentBet ?? null;
        // $message = $message ?? null;
        // $graphic = $graphic ?? null;

        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $data = $callable->postController();

        $this->session->set("TwentyOne", $callable);

        var_dump($_SESSION);

        return $this->render('twentyone.html.twig', [
            'title' => $data["title"] ?? null,
            'header' => $data["header"] ?? null,
            'playerVictories' => $data["standings"]["player"] ?? null,
            'computerVictories' => $data["standings"]["computer"] ?? null,
            'type' => $data["type"] ?? null,
            'playerSum' => $data["playerSum"] ?? 0,
            'computerSum' => $data["computerSum"] ?? 0,
            'lastRoll' => $data["lastRoll"] ?? null,
            'roller' => $data["roller"] ?? null,
            'playerMoney' => $data["playerMoney"] ?? 0,
            'computerMoney' => $data["computerMoney"] ?? 0,
            'currentBet' => $data["currentBet"] ?? 0,
            'message' => $data["message"] ?? null,
            'graphic' => $data["graphic"] ?? null
        ]);
    }

    /**
     * @Route("/yatzy", name="yatzy", methods={"GET", "HEAD"})
    */
    public function yatzy(): Response
    {
        $callable = $_SESSION["Yatzy"] ?? new YatzyGame();
        $_SESSION["Yatzy"] = $callable;

        $data = $callable->renderGame();

        return $this->render('yatzy.html.twig', [
            'header' => $data["header"] ?? null,
            "combinations" => $data["combinations"] ?? null,
            "type" => $data["type"] ?? null,
            "players" => $data["players"] ?? null,
            "message" => $data["message"] ?? null,
            "title" => $data["title"] ?? null,
            "graphic" => $data["graphic"] ?? null,
            "playerCounter" => $data["playerCounter"] ?? null
        ]);
    }

    /**
     * @Route("/yatzy", name="yatzypost", methods={"POST"})
    */
    public function yatzyPost(): Response
    {
        $callable = $_SESSION["Yatzy"] ?? new YatzyGame();
        $_SESSION["Yatzy"] = $callable;

        $data = $callable->postController();

        return $this->render('yatzy.html.twig', [
            'header' => $data["header"] ?? null,
            "combinations" => $data["combinations"] ?? null,
            "type" => $data["type"] ?? null,
            "players" => $data["players"] ?? null,
            "message" => $data["message"] ?? null,
            "title" => $data["title"] ?? null,
            "graphic" => $data["graphic"] ?? null,
            "playerCounter" => $data["playerCounter"] ?? null
        ]);
    }
}
