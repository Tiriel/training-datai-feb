<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $movie = [
            'id' => $id,
            'cover' => 'https://m.media-amazon.com/images/M/MV5BOTA5NjhiOTAtZWM0ZC00MWNhLThiMzEtZDFkOTk2OTU1ZDJkXkEyXkFqcGdeQXVyMTA4NDI1NTQx._V1_SX300.jpg',
            'title' => 'Star Wars - Episode IV : A New Hope',
            'plot' => 'A farmer saves the galaxy by blowing up his father\'s toy.',
            'releasedAt' => new \DateTimeImmutable('25-05-1977'),
            'genre' => [
                'Action',
                'Adventure',
                'Fantasy',
            ]
        ];

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
