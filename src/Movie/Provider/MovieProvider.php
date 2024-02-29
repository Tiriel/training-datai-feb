<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Consumer\OmdbApiConsumer;
use App\Movie\Enum\SearchType;
use App\Movie\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    protected ?SymfonyStyle $io = null;

    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly OmdbApiConsumer $consumer,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
    )
    {
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    public function getOne(SearchType $type, string $value): Movie
    {
        if (($movie = $this->em->getRepository(Movie::class)->findLikeOmdb($type, $value)) instanceof Movie) {
            $this->io?->note('Movie already in database!');

            return $movie;
        }

        $this->io?->text('Searching on OMDb API...');
        $data = $this->consumer->getMovieData($type, $value);
        $this->io?->text('Movie found.');
        $movie = $this->transformer->fromOmdb($data);

        foreach ($this->genreProvider->parseOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->em->persist($movie);
        $this->em->flush();
        $this->io->note('Movie saved in database!');

        return $movie;
    }
}
