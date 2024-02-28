<?php

namespace App\Controller;

use App\Book\BookManager;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    public function __construct(private readonly BookRepository $repository)
    {
    }

    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{id<\d+>?1}', name:  'app_book_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $this->repository->find($id)
        ]);
    }

    #[Route('/title/{title}', name: 'app_book_title', methods: ['GET'])]
    public function title(string $title): Response
    {
        $book = $this->repository->findOneBy(['title' => $title]);

        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }
}
