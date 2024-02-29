<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public function __construct(protected readonly HttpClientInterface $client)
    {
    }

    public function getMovieData(SearchType $type,string $value)
    {
        $data = $this->client->request(
            'GET',
            'https://www.omdbapi.com',
            [
                'query' => [
                    'plot' => 'full',
                    'apikey' => '77e9a2a5',
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
