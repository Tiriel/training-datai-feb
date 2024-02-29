<?php

namespace App\Movie\Transformer;

use App\Entity\Movie;

class OmdbToMovieTransformer implements OmdbTransformerInterface
{
    public const KEYS = [
        'Title',
        'Year',
        'Released',
        'Plot',
        'Rated',
        'imdbID',
        'Country',
        'Poster',
    ];

    public function fromOmdb(mixed $data): Movie
    {
        if (!\is_array($data) || 0 < \count(\array_diff(self::KEYS, \array_keys($data)))) {
            throw new \InvalidArgumentException();
        }

        $date = $data['Released'] === 'N/A' ? '01-01-'.$data['Year'] : $data['Released'];

        return (new Movie())
            ->setTitle($data['Title'])
            ->setPlot($data['Plot'])
            ->setCountry($data['Country'])
            ->setPoster($data['Poster'])
            ->setRated($data['Rated'])
            ->setImdbId($data['imdbID'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ;
    }
}
