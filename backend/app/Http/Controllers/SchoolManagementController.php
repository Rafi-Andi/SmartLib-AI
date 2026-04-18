<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class SchoolManagementController
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin') {
            return response()->json([
                "message" => "Unauthorized role"
            ], 401);
        }

        $school = School::when($request->search, function ($q, $search) {
            $q->where('search', '%' . $search . '%');
        })->withCount('users')->withCount('books')->paginate(8);

        return response()->json([
            "message" => "Sukses mendapatkan data sekolah",
            "data" => $school
        ], 200);
    }

    public function analytics(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unauthorized"
            ], 401);
        }

        $school = $user->school()->select('id', 'name', 'referral_code')->withCount(['books', 'users as studentsCount' => function ($q) {
            $q->where('role', 'student');
        }, 'transactions as borrowsActive' => function ($q) {
            $q->where('status', 'borrowed');
        },  'transactions as borrowsLate' => function ($q) {
            $q->where('status', 'borrowed')
                ->where('due_at', '<', now());
        }])->first();

        return response()->json([
            "message" => "Berhasil ambil data analisa",
            "data" => $school
        ], 200);
    }
}
