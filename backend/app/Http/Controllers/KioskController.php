<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Request;

class KioskController
{
    public function index(Request $request){
        $user = $request->user();
        $studentData = [
        'name' => $user->name,
        'nisn' => $user->nisn,
        "school_name" => $user->school->name,
        'loan_limit' => 3,
        'current_loans_count' => Transaction::where('user_id', $user->id)
            ->where('status', 'borrowed')->count(),
        ];
        $books = Book::where('school_id', $user->school_id)
            ->where("available_count", ">", 0)
        ->latest()
        ->take(6)
        ->get();

        $activeTransactions = Transaction::with('book') 
        ->where('school_id', $user->school_id)
        ->where('user_id', $user->id)
        ->where('status', 'borrowed') 
        ->get();

        return response()->json([
        'status' => 'success',
        'data' => [
            'student' => $studentData,
            'books' => $books,
            'active_loans' => $activeTransactions
        ]
        ]);
    }
}
