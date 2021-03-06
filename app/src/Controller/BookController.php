<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;
use App\Entity\Book;

class BookController extends AbstractController
{
    private $repository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->repository = $bookRepository;
    }

    /**
     * @Route("/book", name="allBooks")
     */
    public function index(): Response
    {
        $books = $this->repository->findAll();
        return $this->render('book/index.html.twig', [
            'header' => 'Books',
            'books' => $books,
            'active' => 'books'
        ]);
    }

    /**
     * @Route("/book/{bookId}", name="viewBook")
     */
    public function viewBook(int $bookId): Response
    {
        $book = $this->repository->find($bookId);
        return $this->render('book/view-book.html.twig', [
            'book' => $book,
            'active' => 'books'
        ]);
    }

    /**
     * @Route("/book-add", name="addBook", methods={"GET", "HEAD"})
     */
    public function addBook(): Response
    {
        return $this->render('book/add-book.html.twig', [
            'header' => 'Add book',
            'message' => 'Fill form to add book!',
            'active' => 'books'
        ]);
    }

    /**
     * @Route("/book-add", name="addBookPost", methods={"POST"})
     */
    public function addBookPost(): Response
    {
        $this->createBook($_POST["title"], $_POST["isbn"], $_POST["author"], $_POST["image"]);

        return $this->redirectToRoute('allBooks');
    }

    public function createBook(string $title, string $isbn, string $author, string $image): void
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createBook(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $book = new Book();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImage($image);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
    }
}
