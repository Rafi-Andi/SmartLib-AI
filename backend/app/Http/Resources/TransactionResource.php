<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dueAt = Carbon::parse($this->due_at);
        $daysRemaining = Carbon::now()->diffInDays($dueAt);
        return [
            "id" => $this->id,
            "borrowed_at" => $this->borrowed_at,
            "due_at" => $this->due_at,
            "returned_at" => $this->returned_at,
            "status" => $this->status,
            "fine_amount" => $this->fine_amount,
            "days_late" => $this->days_late,
            "days_remaining" => (int) $daysRemaining,
            "book" => [
                "title" => $this->book->title,
                "author" => $this->book->author,
            ]
        ];
    }
}
