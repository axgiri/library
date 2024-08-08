<?php

namespace App\Repository;

use App\Enum\StatusEnum;
use App\Models\Borrowing;
use App\Repository\BaseRepository;

class BorrowingRepository extends BaseRepository
{
    public function __construct(Borrowing $model)
    {
        parent::__construct($model);
    }


    public function getBooksByUserId($user_id){
        return $this->model->where('user_id', $user_id)->with('book')->get();
    }

    public function getByStatus($status){
        return $this->model->where('status', $status)->get('created_at');
    }

    public function getByDate($date){
        return $this->model->where('created_at', $date)->getAll();
    }

    public function getBorrowedQuantityById($book_id){
        return $this->model->where('book_id', $book_id)->count();
    }

    public function findBorrowedByUserId($user_id, $book_id){
        return $this->model->where('user_id', $user_id)->where('book_id', $book_id)->where('status', StatusEnum::BORROWED)->first();
    }
}
