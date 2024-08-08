<?php 

namespace App\Http\Service;

use App\Enum\StatusEnum;
use App\Repository\BookRepository;
use App\Repository\BorrowingRepository;
use Carbon\Carbon;

class BorrowService
{
    protected $borrowingRepository;
    protected $bookRepository;
    
    public function __construct(BorrowingRepository $borrowingRepository, BookRepository $bookRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
        $this->bookRepository = $bookRepository;
    }

    public function borrow($user_id, $books)
    {
        $borrowedBooks = [];
        $unavailableBooks = [];
        foreach ($books as $book) {
            $borrowed = $this->borrowingRepository->getBorrowedQuantityById($book['book_id']);
            $name = $this->bookRepository->getName($book['book_id']);
            $quantity = $this->bookRepository->getQuantity($book['book_id']);

            $sixMoths = Carbon::now()->addMonths(1);
            $returnDate = Carbon::parse($book['return_date']);
            
            if ($returnDate->greaterThan($sixMoths)) {
                $unavailableBooks[] = "sorry, $name can not be borrowed for more than 6 moth";
            } elseif ($borrowed >= $quantity) {
                $unavailableBooks[] = "sorry, $name is not available now";
            } else {
                $borrowedBook = $this->borrowingRepository->create([
                    'user_id' => $user_id,
                    'book_id' => $book['book_id'],
                    'return_date' => $book['return_date'],
                    'status' => StatusEnum::BORROWED,
                ]);
                $borrowedBooks[] = $borrowedBook;
            }
        }
        return [
            'borrowed' => $borrowedBooks,
            'unavailable' => $unavailableBooks
        ];
    }

    public function getBooksByUserId($user_id){
        return $this->borrowingRepository->getBooksByUserId($user_id);
    }

    public function returnBooks($user_id, $books)
    {
        $returnedBooks = [];
        $nonReturnable = [];

        foreach ($books as $book) {
            $name = $this->bookRepository->getName($book['book_id']);
            $borrowing = $this->borrowingRepository->findBorrowedByUserId($user_id, $book['book_id']);
            if(!$borrowing){
                $nonReturnable[] = "sorry $name was not borrowed by this user";
            } else{
                $borrowing->status = StatusEnum::RETURNED;
                $borrowing->save();
                $returnedBooks[] = [
                    'book_id'=> $book['book_id'],
                    'name'=> $name,
                ];
            }
        }
        return [
            'returned' => $returnedBooks,
            'nonReturnable'=> $nonReturnable
        ];
    }
}
