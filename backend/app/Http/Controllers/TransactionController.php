<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Book;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;


class TransactionController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unauthorized Role"
            ], 401);
        }

        $transactions = Transaction::query()
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->user_id, function ($q, $user_id) {
                $q->where('user_id', $user_id);
            })
            ->when($request->book_id, function ($q, $book_id) {
                $q->where('book_id', $book_id);
            })
            ->when($request->date_from, function ($q, $date_from) {
                $q->whereDate('borrowed_at', '>=', $date_from);
            })
            ->when($request->date_to, function ($q, $date_to) {
                $q->whereDate('borrowed_at', '<=', $date_to);
            })
            ->where('school_id', $user->school_id)
            ->paginate(10);
        return response()->json([
            "message" => "Berhasil mendapatkan data",
            "data" => $transactions->items()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function borrow(Request $request)
    {


        try {
            $validated = $request->validate([
                "book_id" => "required|exists:books,id",
                "due_days" => "nullable|integer|min:1|max:30"
            ]);

            $validated['due_days'] = $validated['due_days'] ?? 7;

            $user = $request->user();

            if ($user->transactions()->where('returned_at', null)->count() >= 3) {
                return response()->json([
                    "message" => "User sudah meminjam 3 buku (maksimum)"
                ], 422);
            }

            $book = Book::find($validated['book_id']);

            if ($book->available_count <= 0) {
                return response()->json([
                    "message" => "Buku tidak tersedia (stok habis)"
                ], 422);
            }

            if ($book->school_id !== $user->school_id) {
                return response()->json([
                    "message" => "Unauthorized"
                ], 401);
            }

            $dueAt =  Carbon::now()->addDays($validated['due_days']);
            $transaction = Transaction::create([
                "school_id" => $user->school_id,
                "user_id" => $user->id,
                "book_id" => $validated['book_id'],
                "borrowed_at" => Carbon::now(),
                "due_at" => $dueAt,
            ]);

            return response()->json([
                "message" => "Berhasil pinjam buku",
                "transaction" => $transaction
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error",
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function return(Request $request, $id)
    {
        try {
            $user = $request->user();

            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json([
                    "message" => "Transaksi tidak di temukan"
                ], 404);
            }

            if ($transaction->user_id !== $user->id) {
                return response()->json([
                    "message" => "Unauthorized"
                ], 401);
            }

            if ($transaction->status === 'returned') {
                return response()->json([
                    "message" => "Buku telah di kembalikan sebelumnya"
                ], 422);
            }

            $message = $transaction->days_late > 0 ? "Buku berhasil di kembalikan (terlambat {$transaction->days_late} hari)" : "Buku berhasil dikembalikan";

            $transaction->update([
                "returned_at" => Carbon::now(),
                "status" => "returned",
                "fine_amount" => $transaction->days_late * 1000,
            ]);
            return response()->json([
                "message" => $message,
                "data" => [
                    "id" => $transaction->id,
                    "returned_at" => $transaction->returned_at,
                    "status" => $transaction->status,
                    "fine_amount" => $transaction->fine_amount,
                    "days_late" => $transaction->days_late,
                ]
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::query()->with('book')
            ->when($request->status === 'borrowed', function ($q) {
                $q->where('status', 'borrowed');
            })->when($request->status === 'returned', function ($q) {
                $q->where('status', 'returned');
            })
            ->where('user_id', $user->id)->get();

        return response()->json([
            "message" => 'berhasil mendapatkan data',
            "data" => TransactionResource::collection($transactions)
        ], 200);
    }
}
