<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Movie\Enum\SearchType;
use App\Movie\Provider\MovieProvider;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index', methods: ['GET'])]
    public function index(Request $request, MovieRepository $repository): Response
    {
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->getQueryBuilderForPagination()),
            $request->query->get('page', 1),
            6
        );

        return $this->render('movie/index.html.twig', [
            'movies' => $pager,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show', methods: ['GET'])]
    public function show(?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'app_movie_omdb', methods: ['GET'])]
    public function omdb(string $title, MovieProvider $provider): Response
    {
        $movie = $provider->getOne(SearchType::Title, $title);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id<\d+>}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function save(?Movie $movie, Request $request, EntityManagerInterface $manager): Response
    {
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute(
                'app_movie_show',
                ['id' => $movie->getId()]
            );
        }

        return $this->render('movie/save.html.twig', [
            'form' => $form,
        ]);
    }
}
