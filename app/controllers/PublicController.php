<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Mail\IMail;
use App\Mail\Mail;
use App\Models\Filter;
use App\Models\PaginateModel;
use App\Models\PartialBook;
use App\Repository\BookCatalog;
use App\Repository\IBookRepository;

class PublicController extends Controller
{
    /**
     * @var IBookRepository $bookRepository
     */
    private $bookRepository;

    /**
     * @var IMail
     */
    private $mail;

    private const nameDictionaries = ['genres'=>'Genre', 'authors'=>'Author'];

    function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookCatalog();
        $this->mail = new Mail();
    }

    public function actionIndex()
    {
        $this->view();
    }

    public function actionGetBooks(Filter $filter, int $page, int $count) {
        $correctFilter = new Filter();
        $correctFilter->genreId = empty($filter->genreId) ? null : $filter->genreId;
        $correctFilter->authorId = empty($filter->authorId) ? null : $filter->authorId;

        $paginateModel = new PaginateModel();
        $paginateModel->books = $this->bookToPartialBook($this->bookRepository->getBooksByFilterPager($correctFilter, $page, $count));
        $paginateModel->totalItems = $this->bookRepository->getQuantityByFilter($correctFilter);
        echo json_encode($paginateModel);
    }

    public function actionGetBookDetails(int $id) {
        echo json_encode($this->bookRepository->getBook($id));
    }

    public function actionGetDictionary(string $name)
    {
        echo json_encode($this->bookRepository
            ->getDictionary(PublicController::nameDictionaries[$name]));
    }

    public function actionOrder(string $bookId, int $quantity, string $userName, string $userAddress)
    {
        $book = $this->bookRepository->getBook($bookId);

        $message = "Book name: | $book->name(ID: $book->id)\r\n";
        $message .= "Quantity:  | $quantity\r\n";
        $message .= "---------------------------------------------------------------\r\n";
        $message .= "Price:     | $book->price        |TOTAL PRICE: " . $book->price * $quantity . "\r\n";
        $message .= "---------------------------------------------------------------\r\n";
        $message .= "---------------------------------------------------------------\r\n\n\n";
        $message .= "---------------------------------------------------------------\r\n";
        $message .= "---------------------------------------------------------------\r\n";
        $message .= "User name:    | $userName\r\n";
        $message .= "User address: | $userAddress\r\n";
        $message .= "---------------------------------------------------------------\r\n\n\n";
        $message .= date("d-M-Y H:i:s") . "\r\n";
        $message .= "-----------------------------------\r\n\n";

        $this->mail->send("user@example.com", "Order book", $message);
    }

    private function bookToPartialBook(array $books) : array
    {
        $partialArrayToString = function (array $array) : string {
            $targetCount = 3;
            $count = count($array);

            if (!$count)
                return '';

            if ($count > $targetCount)
                $array = array_map(
                    function ($key) use($array) { return $array[$key]; },
                    array_rand($array, $targetCount));
            $str = implode(
                ', ',
                array_map(function ($item) { return $item->name; }, $array)); // . ($count > $targetCount ? '...' : '');

            return strlen($str) > 47
                ? substr($str, 0, 47) . '...'
                : $str . (($count > $targetCount)
                    ? '...'
                    : '');
        };

        return array_map(function($book) use($partialArrayToString) : PartialBook {
            $partialBook = new PartialBook();
            $partialBook->id = $book->id;
            $partialBook->name = substr($book->name, 0, 50) . (strlen($book->name) > 47 ? '...' : '');
            $partialBook->genres = $partialArrayToString($book->genres);
            $partialBook->authors = $partialArrayToString($book->authors);
            $partialBook->description = substr($book->description, 0, 90) . (strlen($book->description) > 80 ? '...' : '');
            $partialBook->price = $book->price;
            return $partialBook;
        }, $books);
    }
}
