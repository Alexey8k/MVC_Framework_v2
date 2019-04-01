<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Repository\{IBookRepository, BookCatalog};

class AdminToolController extends Controller
{
    /**
     * @var IBookRepository
     */
    private $_bookRepository;

    function __construct()
    {
        parent::__construct();
        $this->_bookRepository = new BookCatalog();
    }

    public function actionIndex() {
        $books = $this->_bookRepository->getBooks();
        $this->view($books);
    }

    public function actionEditBookGet(int $id = null) {
        $book = empty($id) ? new Book() : $this->_bookRepository->getBook($id);
        $this->view($book);
    }

    public function actionEditBookPost(Book $book)
    {
        $this->_bookRepository->saveBook($book);
        $this->redirectToAction('Index', 'AdminTool');
    }

    public function actionDeleteBook(int $id) {
        $this->_bookRepository->deleteBook($id);
        $this->redirectToAction('Index', 'AdminTool');
    }

    public function actionAddBook() {
        $this->redirectToAction('EditBook', 'AdminTool');
    }
}
