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
            'playerName' => $data["playerName"] ?? null,
            'playerVictories' => $data["standings"]["player"] ?? null,
            'computerVictories' => $data["standings"]["computer"] ?? null,
            'type' => $data["type"] ?? null,
            'playerSum' => $data["playerSum"] ?? 0,
            'computerSum' => $data["computerSum"] ?? 0,
            'lastRoll' => $data["lastRoll"] ?? null,
            'roller' => $data["roller"] ?? null,
            'playerMoney' => $data["playerMoney"] ?? 0,
            'maxPlayerMoney' => $data["maxPlayerMoney"] ?? null,
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

        return $this->render('twentyone/index.html.twig', $data);
    }

    /**
    * @Route(
    *      "/clear",
    *      name="twentyone_reset",
    *      methods={"POST"}
    * )
    */
    public function clear(): Response
    {
        $this->session->clear();

        return $this->redirectToRoute('twentyone');
    }

    /**
    * @Route(
    *      "/twentyone/turn",
    *      name="twentyone_turn",
    *      methods={"GET"}
    * )
    */
    public function turn(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $res = $callable->renderGame();

        $this->session->set("TwentyOne", $callable);

        $data = $this->dataToRender($res);

        return $this->render('twentyone/turn.html.twig', $data);
    }

    /**
    * @Route(
    *      "/twentyone/game",
    *      name="twentyone_game",
    *      methods={"GET"}
    * )
    */
    public function game(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $res = $callable->renderGame();

        $this->session->set("TwentyOne", $callable);

        $data = $this->dataToRender($res);

        return $this->render('twentyone/game.html.twig', $data);
    }

    /**
    * @Route(
    *      "/twentyone/end",
    *      name="twentyone_end",
    *      methods={"GET"}
    * )
    */
    public function end(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();


        $res = $callable->renderGame();

        $this->session->set("TwentyOne", $callable);

        $data = $this->dataToRender($res);

        return $this->render('twentyone/end.html.twig', $data);
    }


    /**
    * @Route(
    *      "/twentyone/start",
    *      name="twentyone_start",
    *      methods={"POST"}
    * )
    */
    public function start(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();

        $name = $_POST["name"] ?? null;

        $callable->setPlayer($name);

        $this->session->set("TwentyOne", $callable);

        return $this->redirectToRoute('twentyone_turn');
    }

    /**
    * @Route(
    *      "/twentyone/turn",
    *      name="twentyone_turnsetup",
    *      methods={"POST"}
    * )
    */
    public function turnSetup(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();

        $dices = $_POST["dices"] ?? "1";
        $dices = intval($dices);
        $name = $_POST["name"] ?? null;

        $callable->start($dices, intval($_POST["bet"]), $name);

        $this->session->set("TwentyOne", $callable);

        return $this->redirectToRoute('twentyone_game',);
    }

    /**
    * @Route(
    *      "/twentyone/roll",
    *      name="twentyone_roll",
    *      methods={"POST"}
    * )
    */
    public function roll(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();

        $callable->roll();

        if ($callable->getSum("player") >=21) {
            return $this->redirectToRoute('twentyone_end',);
        }

        $this->session->set("TwentyOne", $callable);

        return $this->redirectToRoute('twentyone_game');
    }

    /**
    * @Route(
    *      "/twentyone/stop",
    *      name="twentyone_stop",
    *      methods={"POST"}
    * )
    */
    public function stop(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();

        $callable->changeRoller("computer");
        $callable->roll();

        $this->session->set("TwentyOne", $callable);

        // return new Response("Test");

        return $this->redirectToRoute('twentyone_end');
    }

    /**
    * @Route(
    *      "/twentyone/menu",
    *      name="twentyone_menu",
    *      methods={"POST"}
    * )
    */
    public function menu(): Response
    {
        $callable = $this->session->get("TwentyOne") ?? new TwentyOneGame();

        $callable->clearBet();

        $this->session->set("TwentyOne", $callable);

        return $this->redirectToRoute('twentyone_turn',);
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
}
