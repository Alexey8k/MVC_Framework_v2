<?php

namespace App\Repository;

use App\Models\{Book, Filter, PaginateModel, PairIdName};

interface IBookRepository
{
    /**
     * @param \int $id
     * @return Book
     */
    function getBook(int $id) : Book;

    /**
     * @return Book[]
     */
    function getBooks() : array;

    /**
     * @param Book $book
     * @return void
     */
    function saveBook(Book $book) : void;

    /**
     * @param \string $tableName
     * @return PairIdName[]
     */
    public function getDictionary(string $tableName) : array;

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $count
     * @return Book[]
     */
    public function getBooksByFilterPager(Filter $filter, int $page, int $count) : array;

    /**
     * @param Filter $filter
     * @return int
     */
    public function getQuantityByFilter(Filter $filter) : int;

    /**
     * @param int $id
     * @return void
     */
    public function deleteBook(int $id) : void;
}