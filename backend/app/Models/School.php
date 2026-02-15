<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $guarded = ['id'];

    public function users(){
        return $this->hasMany(User::class);
    }
    public function books(){
        return $this->hasMany(Book::class);
    }
}