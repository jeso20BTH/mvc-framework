<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HighScoreRepository;
use App\Entity\HighScore;

class HighScoreController extends AbstractController
{
    private $repository;
    private object $session;

    public function __construct(HighScoreRepository $highScoreRepository, SessionInterface $session)
    {
        $this->repository = $highScoreRepository;
        $this->session = $session;
    }
    /**
     * @Route("/highscore/{game}", name="high_score")
     */
    public function index(string $game): Response
    {
        $limit = 10;
        $scores = $this->repository->findBy(
            ['game' => $game],
            ['score' => 'DESC'],
            $limit
        );



        return $this->render('high_score/index.html.twig', [
            'header' => ($game == 'yatzy') ? $game . ' highscore!' : '21' . ' highscore!',
            'scores' => $scores,
            'game' => $game,
            'active' => 'highscore'
        ]);
    }

    /**
     * @Route("/highscore-add", name="addScore", methods={"POST"})
     */
    public function addScore(): Response
    {
        if ($_POST["game"] == 'twentyone') {
            $this->addHighScore($_POST["name"], intval($_POST["score"]), $_POST["game"]);
        } elseif ($_POST["game"] == 'yatzy') {
            foreach ($this->session->get("highscores") as $player) {
                $this->addHighScore($player["name"], $player["score"], $_POST["game"]);
            }
        }


        $this->session->clear();

        return $this->redirectToRoute($_POST["game"]);
    }

    public function addHighScore(string $name, int $score, string $game): void
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createBook(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $highScore = new Highscore();
        $highScore->setName($name);
        $highScore->setScore($score);
        $highScore->setGame($game);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($highScore);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();


    }
}
