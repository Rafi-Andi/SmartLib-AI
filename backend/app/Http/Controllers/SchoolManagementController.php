<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

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
}
