<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unauthorized role"
            ], 401);
        }

        $users = User::when($request->role, function ($q, $role) {
            $q->where('role', $role);
        })->when($request->search, function ($q, $search) {
            $q->where('name', 'like', '%' . $search . '%');
        })->when($request->boolean('is_rfid'), function($q) {
            $q->whereNull('rfid_uid');
        })
        ->where("school_id", $user->school_id)->orderBy('created_at', 'desc')->paginate(5);

        return response()->json([
            "message" => "Berhasil mendapatkan data user",
            "data" => $users
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|unique:users",
            "password" => "required|string|min:6",
            "nisn" => "nullable|string|max:20",
            "role" => "required|string|in:student,admin"
        ]);

        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unauthorized role"
            ], 401);
        }
        $school = $user->school;

        if (!$school) {
            return response()->json([
                "message" => "Admin belum terhubung dengan sekolah"
            ], 422);
        }
        $school->users()->create([
            "name" => $validated['name'],
            "nisn" => $validated['nisn'],
            "email" => $validated['email'],
            "password" => bcrypt($validated['password']),
            "role" => $validated['role']
        ]);

        return response()->json([
            "message" => "Success create users"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => "nullable|string|max:255",
            "email" => "nullable|string|unique:users,email,{$id}",
            "referral_code" => "nullable|string",
            "nisn" => "nullable|string|max:20",
            "is_active" => "nullable|boolean",
            "rfid_uid" => "nullable|string"
        ]);

        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unauthorized role"
            ], 401);
        }
        $student = User::find($id);

        if (!$student) {
            return response()->json([
                "User tidak ditemukan"
            ], 404);
        }

        if ($student->school_id !== $user->school_id) {
            return response()->json([
                "message" => "Tidak bisa akses edit user tersebut"
            ], 401);
        }

        $student->update($validated);
        return response()->json([
            "message" => "Berhasil update user"
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unauthorized role"
            ], 401);
        }
        $student = User::find($id);

        if (!$student) {
            return response()->json([
                "User tidak ditemukan"
            ], 404);
        }

        if ($student->school_id !== $user->school_id) {
            return response()->json([
                "message" => "Tidak bisa akses edit user tersebut"
            ], 401);
        }

        $student->delete();
        return response()->json([
            "message" => "Berhasil hapus user"
        ], 200);
    }
}