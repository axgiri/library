<?php 

namespace App\Http\Service;

use App\Repository\BookRepository;

class BookService{

    protected BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository){
        $this->bookRepository = $bookRepository;
    }

    public function getAllBooks(){
        return $this->bookRepository->getAll();
    }

    public function getBookById($id){
        return $this->bookRepository->find($id);
    }

    public function create(array $attributes){
        return $this->bookRepository->create($attributes);
    }

    public function update($id, array $attributes){
        return $this->bookRepository->update($id, $attributes);
    }

    public function delete($id){
        return $this->bookRepository->delete($id);
    }
}
