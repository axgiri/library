<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    
    public function rules(): array {
        return [
            'user_id' => 'required|exists:users,id',
            'books' => 'required|array',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.return_date' => 'sometimes|date'
        ];
    }
}