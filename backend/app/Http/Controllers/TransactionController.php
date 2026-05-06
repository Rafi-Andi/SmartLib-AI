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
            ->with('user', 'book')
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->search, function ($q, $search) {
            $q->where(function ($query) use ($search) {
                $query->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('book', function ($b) use ($search) {
                    $b->where('title', 'like', '%' . $search . '%');
                });
            });
        })
            ->when($request->book_id, function ($q, $book_id) {
                $q->where('book_id', $book_id);
            })
            ->when($request->has('has_fine'), function ($q) {
                $q->where('fine_amount', '>', 0);
            })
            ->when($request->has('fine_paid'), function ($q) use ($request) {
                $q->where('fine_paid', filter_var($request->fine_paid, FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->date_from, function ($q, $date_from) {
                $q->whereDate('borrowed_at', '>=', $date_from);
            })
            ->when($request->date_to, function ($q, $date_to) {
                $q->whereDate('borrowed_at', '<=', $date_to);
            })
            ->where('school_id', $user->school_id)
            ->latest()
            ->paginate(5);
        return response()->json([
            "message" => "Berhasil mendapatkan data",
            "data" => $transactions
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

    public function payFine(Request $request, $id)
    {
        try {
            $user = $request->user();
            if ($user->role !== 'admin') {
                return response()->json(["message" => "Unauthorized"], 401);
            }

            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json(["message" => "Transaksi tidak ditemukan"], 404);
            }

            if ($transaction->fine_amount <= 0) {
                return response()->json(["message" => "Tidak ada denda untuk transaksi ini"], 422);
            }

            if ($transaction->fine_paid) {
                return response()->json(["message" => "Denda sudah dibayar sebelumnya"], 422);
            }

            $transaction->update([
                "fine_paid" => true
            ]);

            return response()->json([
                "message" => "Pembayaran denda berhasil dicatat",
                "data" => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Terjadi kesalahan",
                "error" => $e->getMessage()
            ], 500);
        }
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

            $book->decrement('available_count');

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

            $transaction->book->increment('available_count');

            $transaction->load(['book', 'user', 'school']);

            return response()->json([
                "message" => $message,
                "data" => [
                    "id" => $transaction->id,
                    "returned_at" => $transaction->returned_at,
                    "status" => $transaction->status,
                    "fine_amount" => $transaction->fine_amount,
                    "days_late" => $transaction->days_late,
                    "book_title" => $transaction->book->title,
                    "book_author" => $transaction->book->author,
                    "student_name" => $transaction->user->name,
                    "student_nisn" => $transaction->user->nisn,
                    "school_name" => $transaction->school->name ?? 'SmartLib AI Library'
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Terjadi kesalahan saat memproses pengembalian",
                "error" => $e->getMessage()
            ], 500);
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
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            "message" => 'berhasil mendapatkan data',
            "data" => TransactionResource::collection($transactions)
        ], 200);
    }

    public function analytics(Request $request)
{
    $user = $request->user();
    $today = now()->toDateString();

    $stats = Transaction::where('school_id', $user->school_id)
        ->selectRaw("
            COUNT(*) as total_transactions,
            SUM(CASE WHEN status = 'borrowed' THEN 1 ELSE 0 END) as total_borrowed,
            SUM(CASE WHEN status = 'returned' AND DATE(returned_at) = '$today' THEN 1 ELSE 0 END) as returned_today,
            SUM(CASE WHEN status = 'borrowed' AND due_at < NOW() THEN 1 ELSE 0 END) as total_late
        ")
        ->first();

    return response()->json([
        "message" => "Berhasil mendapatkan statistik",
        "data" => [
            "total" => $stats->total_transactions ?? 0,
            "borrowed" => $stats->total_borrowed ?? 0,
            "late" => $stats->total_late ?? 0,
            "returned_today" => $stats->returned_today ?? 0,
        ]
    ], 200);
}
}
