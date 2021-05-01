<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Game\TwentyOneGame;
use App\Game\YatzyGame;

class GameController extends AbstractController
{
    private object $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function dataToRender(array $data)
    {
        return [
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
        ];
    }

    /**
     * @Route("/", name="index")
    */
    public function greeting(): Response
    {
        $this->session->clear();

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
        // var_dump($this->session);
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $res = $callable->renderGame();
        $this->session->set("TwentyOne", $callable);

        $data = $this->dataToRender($res);

        return $this->render('twentyone.html.twig', $data);
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
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $res = $callable->postController();

        $this->session->set("TwentyOne", $callable);

        $data = $this->dataToRender($res);

        return $this->render('twentyone.html.twig', $data);
    }

    /**
     * @Route("/yatzy", name="yatzy", methods={"GET", "HEAD"})
    */
    public function yatzy(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $data = $callable->renderGame();
        $this->session->set("Yatzy", $callable);

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
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $data = $callable->postController();
        $this->session->set("Yatzy", $callable);

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
