<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['borrowed_count'];
    public function getBorrowedCountAttribute(){
        return $this->stock_count - $this->available_count;
    }
}