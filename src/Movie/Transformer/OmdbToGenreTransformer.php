<?php

namespace App\Movie\Transformer;

use App\Entity\Genre;

class OmdbToGenreTransformer implements OmdbTransformerInterface
{

    public function fromOmdb(mixed $data): Genre
    {
        if (!is_string($data) || str_contains($data, ',')) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($data);
    }
}
