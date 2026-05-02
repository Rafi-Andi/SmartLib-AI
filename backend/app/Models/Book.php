<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['borrowed_count', 'cover_image_url'];

    public function getBorrowedCountAttribute(){
        return $this->stock_count - $this->available_count;
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_img) {
            return url('storage/' . $this->cover_img);
        }
        return null;
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}