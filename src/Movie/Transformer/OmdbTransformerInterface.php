<?php

namespace App\Movie\Transformer;

interface OmdbTransformerInterface
{
    public function fromOmdb(mixed $data): object;
}
