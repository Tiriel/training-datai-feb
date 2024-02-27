<?php

namespace App\Controller;

use App\Book\BookManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{id<\d+>?1}', name:  'app_book_show', methods: ['GET'])]
    public function show(int $id, BookManager $manager): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::show : '.$manager->getBook($id)
        ]);
    }
}
