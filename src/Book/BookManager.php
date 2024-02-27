<?php

namespace App\Book;

class BookManager
{
    public function getBook(int $id): string
    {
        return 'id : '.$id;
    }
}
