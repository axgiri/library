<?php 

namespace App\Http\Service;

use App\Enum\StatusEnum;
use App\Repository\BookRepository;
use App\Repository\BorrowingRepository;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Exists;

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
        $overDueBooks = [];
        foreach ($books as $book) {
            $borrowed = $this->borrowingRepository->getBorrowedQuantityById($book['book_id']);
            $name = $this->bookRepository->getName($book['book_id']);
            $quantity = $this->bookRepository->getQuantity($book['book_id']);

            $sixMoths = Carbon::now()->addMonths(1);
            $returnDate = Carbon::parse($book['return_date']);
            if ($returnDate->greaterThan($sixMoths)) {
                $unavailableBooks[] = "sorry, $name can not be borrowed for more than 6 moth";
            } elseif ($borrowed >= $quantity) {
                $unavailableBooks[] = "sorry, $name is not available now. but you got reservation";
                $this->reservation($user_id, $books);
            } elseif($this->borrowingRepository->getByStatus($user_id) >= 1) {
                $overDueBooks[] = "please, return other books";
            }
            
            else {
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
            'unavailable' => $unavailableBooks,
            'overdue' => $overDueBooks,
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

    public function reservation($user_id, $books) {
        $reservedBooks = [];
        foreach ($books as $book) {
            $reserved = $this->borrowingRepository->create([
                'user_id' => $user_id,
                'book_id' => $book['book_id'],
                'return_date' => $book['return_date'],
                'status' => StatusEnum::RESERVED,
            ]);
            $reservedBooks[] = [$reserved];
        }
        return [
            'reserved' => $reservedBooks,
        ];
    }

    public function deleteReservation($user_id, $books) {
        foreach ($books as $book) {
            $this->borrowingRepository->deleteReservation($user_id, $book['book_id']);
        }
        return;
    }
}
