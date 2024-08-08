<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'return_date',
        'status',
    ];
    
    public $incrementing = false;
    protected $primatyKey = ['user_id','book_id'];


    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
