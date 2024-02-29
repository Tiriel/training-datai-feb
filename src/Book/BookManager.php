<?php

namespace App\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Autoconfigure(lazy: true)]
class BookManager
{
    public function __construct(
        #[Autowire(lazy: true)]
        protected readonly BookRepository $repository,
        #[Autowire(param: 'items_per_page')]
        protected readonly int $limit,
    )
    {
    }

    public function getBook(int $id): ?Book
    {
        return $this->repository->find($id);
    }

    public function findPaginated(int $page): iterable
    {
        return $this->repository->findBy(
            [],
            [],
            $this->limit,
            ($page - 1) * $this->limit
        );
    }

    public function doStuff():void
    {
        // ..
    }
}
