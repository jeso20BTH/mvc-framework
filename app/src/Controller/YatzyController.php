<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Game\TwentyOneGame;
use App\Game\YatzyGame;

class YatzyController extends AbstractController
{
    private object $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function dataToRender(array $data)
    {
        return [
            'header' => $data["header"] ?? null,
            "combinations" => $data["combinations"] ?? null,
            "type" => $data["type"] ?? null,
            "players" => $data["players"] ?? null,
            "message" => $data["message"] ?? null,
            "title" => $data["title"] ?? null,
            "graphic" => $data["graphic"] ?? null,
            "playerCounter" => $data["playerCounter"] ?? null
        ];
    }

    /**
     * @Route("/yatzy", name="yatzy", methods={"GET", "HEAD"})
    */
    public function index(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $res = $callable->renderGame();
        $this->session->set("Yatzy", $callable);

        $data = $this->dataToRender($res);

        return $this->render('yatzy/index.html.twig', $data);
    }

    /**
     * @Route("/yatzy-add", name="yatzy_add", methods={"GET", "HEAD"})
    */
    public function add(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $data = $callable->renderGame();
        $this->session->set("Yatzy", $callable);

        return $this->render('yatzy/add.html.twig', $data);
    }

    /**
     * @Route("/yatzy-game", name="yatzy_game", methods={"GET", "HEAD"})
    */
    public function game(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $data = $callable->renderGame();
        $this->session->set("Yatzy", $callable);

        var_dump($callable->getType());

        if ($callable->getType() == "summary") {
            return $this->redirectToRoute('yatzy_end');
        }

        return $this->render('yatzy/game.html.twig', $data);
    }

    /**
     * @Route("/yatzy-place", name="yatzy_place", methods={"GET", "HEAD"})
    */
    public function place(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $data = $callable->renderGame();
        $this->session->set("Yatzy", $callable);

        var_dump($callable->getType());

        if ($callable->getType() == "summary") {
            return $this->redirectToRoute('yatzy_end');
        }

        return $this->render('yatzy/place.html.twig', $data);
    }

    /**
     * @Route("/yatzy-end", name="yatzy_end", methods={"GET", "HEAD"})
    */
    public function end(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $data = $callable->renderGame();
        $this->session->set("Yatzy", $callable);

        $this->session->set("highscores", $callable->setHighscores());

        return $this->render('yatzy/end.html.twig', $data);
    }

    /**
     * @Route("/yatzy-add", name="yatzy_add_post", methods={"POST"})
    */
    public function addPost(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $callable->addPlayer($_POST["type"], $_POST["name"]);

        $this->session->set("Yatzy", $callable);

        return $this->redirectToRoute('yatzy');
    }

    /**
     * @Route("/yatzy-start", name="yatzy_start_post", methods={"POST"})
    */
    public function startPost(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $callable->startTurn();

        $this->session->set("Yatzy", $callable);

        return $this->redirectToRoute('yatzy_game');
    }

    /**
     * @Route("/yatzy-keep", name="yatzy_keep_post", methods={"POST"})
    */
    public function keepPost(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $dices = $_POST["dices"] ?? [];
        $dicesToRoll = $callable->getDicesToRoll($dices);
        $callable->setDicesToRoll($dicesToRoll);
        $callable->roll();

        $this->session->set("Yatzy", $callable);

        if ($callable->getType() == "place") {
            return $this->redirectToRoute('yatzy_place');
        }
        return $this->redirectToRoute('yatzy_game');
    }

    /**
     * @Route("/yatzy-place", name="yatzy_place_post", methods={"POST"})
    */
    public function placePost(): Response
    {
        $callable = $this->session->get("Yatzy") ?? new YatzyGame();

        $callable->endTurn($_POST["placement"]);

        $this->session->set("Yatzy", $callable);

        if ($callable->getType() == "place") {
            return $this->redirectToRoute('yatzy_place');
        } elseif ($callable->getType() == "summary") {
            return $this->redirectToRoute('yatzy_end');
        }

        return $this->redirectToRoute('yatzy_game');
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
