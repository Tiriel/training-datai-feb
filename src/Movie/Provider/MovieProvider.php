<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Consumer\OmdbApiConsumer;
use App\Movie\Enum\SearchType;
use App\Movie\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly OmdbApiConsumer $consumer,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
    )
    {
    }

    public function getOne(SearchType $type, string $value): Movie
    {
        if (($movie = $this->em->getRepository(Movie::class)->findLikeOmdb($type, $value)) instanceof Movie) {
            return $movie;
        }

        $data = $this->consumer->getMovieData($type, $value);
        $movie = $this->transformer->fromOmdb($data);

        foreach ($this->genreProvider->parseOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->em->persist($movie);
        $this->em->flush();

        return $movie;
    }
}
