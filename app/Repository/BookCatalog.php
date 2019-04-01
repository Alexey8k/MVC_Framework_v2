<?php

namespace App\Repository;

use App\Core\Repository\Repository;
use App\Models\{Book, Filter, PaginateModel, PairIdName};

class BookCatalog extends Repository implements IBookRepository
{
    /**
     * @var callable $pairNameIdMapper
     */
    private $pairNameIdMapper;

    /**
     * @var callable $bookMapper
     */
    private $bookMapper;

    public function __construct()
    {
        parent::__construct();

        $this->pairNameIdMapper = function (array $row) : PairIdName {
            $pairNameId = new PairIdName();
            $pairNameId->id = intval($row['id']);
            $pairNameId->name = $row['name'];
            return $pairNameId;
        };

        $this->bookMapper = function (array $row) : Book {
            $book = new Book();
            $book->id = intval($row['id']);
            $book->name = $row['name'];
            $book->description = $row['description'];
            $book->price = doubleval($row['price']);
            return $book;
        };
    }

    /**
     * @param \int $id
     * @return Book
     */
    function getBook(int $id) : Book
    {
        $data = $this->storedProcedureCall('GetBook')
            ->returningResultSet('product', $this->bookMapper)
            ->returningResultSet('authors', $this->pairNameIdMapper)
            ->returningResultSet('genres', $this->pairNameIdMapper)
            ->addParam('i', $id)
            ->execute();

        /**
         * @var Book $book
         */
        $book = $data['product'][0];
        $book->authors = $data['authors'];
        $book->genres = $data['genres'];

        return $book;
    }

    /**
     * @return Book[]
     */
    function getBooks() : array
    {
        return $this->executeQuery("SELECT * FROM `Book`", $this->bookMapper);
    }

    /**
     * @param Book $book
     * @return void
     */
    function saveBook(Book $book): void
    {
        $bookId = $this->storedProcedureCall('SaveBook',true)
            ->addParam('i',$book->id)
            ->addParam('s',$book->name)
            ->addParam('s',$book->description)
            ->addParam('d',$book->price)
            ->execute();

        foreach ($book->authors ?? [] as $authorId)
            $this->storedProcedureCall('AddAuthorsToBook')
                ->addParam('i', $bookId)
                ->addParam('s', $authorId)
                ->execute();

        foreach ($book->genres ?? [] as $genreId)
            $this->storedProcedureCall('AddGenresToBook')
                ->addParam('i', $bookId)
                ->addParam('s', $genreId)
                ->execute();
    }

    /**
     * @param string $tableName
     * @return PairIdName[]
     */
    public function getDictionary(string $tableName) : array
    {
        return $this->executeQuery("SELECT * FROM `$tableName`", $this->pairNameIdMapper);
    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $count
     * @return Book[]
     */
    public function getBooksByFilterPager(Filter $filter, int $page, int $count) : array
    {
        $books = $this->storedProcedureCall('GetBooksByFilterPager')
            ->returningResultSet('books', $this->bookMapper)
            ->addParam('i', $filter->genreId)
            ->addParam('i', $filter->authorId)
            ->addParam('i', $page)
            ->addParam('i', $count)
            ->execute()['books'];

        return array_map(function (Book $book) {
            $book->genres = $this->storedProcedureCall('GetGenresByBook')
                ->returningResultSet('genres', $this->pairNameIdMapper)
                ->addParam('i', $book->id)
                ->execute()['genres'];
            $book->authors = $this->storedProcedureCall('GetAuthorsByBook')
                ->returningResultSet('authors', $this->pairNameIdMapper)
                ->addParam('i', $book->id)
                ->execute()['authors'];
            return $book;
        }, $books);
    }

    /**
     * @param Filter $filter
     * @return int
     */
    public function getQuantityByFilter(Filter $filter) : int
    {
        return $this->storedProcedureCall('GetQuantityByFilter', true)
            ->addParam('i', $filter->genreId)
            ->addParam('i', $filter->authorId)
            ->execute();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteBook(int $id) : void {
        $this->executeNonQuery("DELETE FROM `BookGenre` WHERE `bookId`=$id");
        $this->executeNonQuery("DELETE FROM `BookAuthor` WHERE `bookId`=$id");
        $this->executeNonQuery("DELETE FROM `Book` WHERE `id`=$id");
    }
}