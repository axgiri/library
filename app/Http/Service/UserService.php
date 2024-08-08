<?php 

namespace App\Http\Service;

use App\Repository\UserRepository;

class UserService{

    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function create(array $attributes){
        $user = $this->userRepository->create($attributes);
        return $user;
    }
}
