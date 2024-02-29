<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[When('dev')]
#[When('prod')]
#[AsDecorator(OmdbApiConsumer::class, priority: 10)]
class TraceableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        protected readonly OmdbApiConsumer $consumer,
        protected readonly LoggerInterface $logger,
    )
    {
    }

    public function getMovieData(SearchType $type, string $value)
    {
        $this->logger->info(sprintf("New request : %s=%s", $type->getParam(), $value));

        return $this->consumer->getMovieData($type, $value);
    }
}
