<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public function __construct(
        protected readonly HttpClientInterface $omdbClient,
    )
    {
    }

    public function getMovieData(SearchType $type,string $value)
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            [
                'query' => [
                    $type->getParam() => $value,
                ]
            ]
        )->toArray();

        if (\array_key_exists('Error', $data)) {
            if ('Movie not found!' === $data['Error']) {
                throw new NotFoundHttpException('Movie not found!');
            }

            throw new \RuntimeException($data['Error']);
        }

        return $data;
    }
}
