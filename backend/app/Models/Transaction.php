<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected $appends = ['days_late'];

    public function getDaysLateAttribute()
    {
        $dueDate = Carbon::parse($this->due_at);

        $compareDate = $this->returned_at
            ? Carbon::parse($this->returned_at)
            : now();

        $daysLate = $dueDate->diffInDays($compareDate, false);

        return (int) max($daysLate, 0);
    }
}
