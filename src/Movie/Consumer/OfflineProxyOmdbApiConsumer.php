<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

//#[When('dev')]
//#[When('test')]
//#[AsDecorator(OmdbApiConsumer::class, priority: 15)]
class OfflineProxyOmdbApiConsumer extends OmdbApiConsumer
{
    private const MOCK = <<<EOD
{"Title":"Star Wars: Episode IV - A New Hope","Year":"1977","Rated":"PG","Released":"25 May 1977","Runtime":"121 min","Genre":"Action, Adventure, Fantasy","Director":"George Lucas","Writer":"George Lucas","Actors":"Mark Hamill, Harrison Ford, Carrie Fisher","Plot":"The Imperial Forces, under orders from cruel Darth Vader, hold Princess Leia hostage in their efforts to quell the rebellion against the Galactic Empire. Luke Skywalker and Han Solo, captain of the Millennium Falcon, work together with the companionable droid duo R2-D2 and C-3PO to rescue the beautiful princess, help the Rebel Alliance and restore freedom and justice to the Galaxy.","Language":"English","Country":"United States","Awards":"Won 6 Oscars. 66 wins & 31 nominations total","Poster":"https://m.media-amazon.com/images/M/MV5BOTA5NjhiOTAtZWM0ZC00MWNhLThiMzEtZDFkOTk2OTU1ZDJkXkEyXkFqcGdeQXVyMTA4NDI1NTQx._V1_SX300.jpg","Ratings":[{"Source":"Internet Movie Database","Value":"8.6/10"},{"Source":"Rotten Tomatoes","Value":"93%"},{"Source":"Metacritic","Value":"90/100"}],"Metascore":"90","imdbRating":"8.6","imdbVotes":"1,435,882","imdbID":"tt0076759","Type":"movie","DVD":"10 Oct 2016","BoxOffice":"$460,998,507","Production":"N/A","Website":"N/A","Response":"True"}
EOD;

    public function __construct()
    {
    }

    public function getMovieData(SearchType $type, string $value)
    {
        return \json_decode(self::MOCK, true);
    }

}
