<?php

namespace App\Repository;

use App\Models\Book;
use App\Repository\BaseRepository;

class BookRepository extends BaseRepository
{
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }

    public function getQuantity($id){
        return $this->model->where("id",$id)->value('quantity');
    }

    public function getName($id){
        return $this->model->where('id',$id)->value('name');
    }
}
