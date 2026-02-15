<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class AuthController
{
    public function registerSchool(Request $request)
    {
        $validated = $request->validate([
            "school_name" => 'required|string|max:255',
            "school_address" => 'nullable|string|max:500',
            "school_phone" => "nullable|string|max:20",
            "school_email" => "nullable|string|max:255",
            "admin_name" => "required|string|max:255",
            "admin_email" => "required|email|unique:users,email",
            "password" => "required|string|min:6"
        ]);

        $referralCode = Str::of($validated['school_name'])->replace(' ', '')->upper() . "-" . strtoupper(Str::random(6));

        $school = School::create([
            "name" => $validated['school_name'],
            "slug" => Str::slug($validated['school_name']),
            "referral_code" => $referralCode,
            "kiosk_pin" => rand(1000, 9999),
            "address" => $validated['school_address'],
            "phone" => $validated['school_phone'],
            "email" => $validated['school_email'],
        ]);

        $user = User::create([
            "school_id" => $school->id,
            "name" => $validated['admin_name'],
            "email" => $validated['admin_email'],
            "password" => bcrypt($validated['password']),
            "role" => 'admin',
        ]);

        return response()->json([
            "message" => "Sekolah berhasil didaftarkan",
            "data" => [
                "school" => [
                    "id" => $school->id,
                    "name" => $school->name,
                    "referral_code" => $school->referral_code,
                    "kiosk_pin" => $school->kiosk_pin,
                    "kiosk_enabled" => $school->kiosk_enabled,
                ],
                "user" => [
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => $user->role
                ]
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|unique:users",
            "password" => "required|string|min:6",
            "referral_code" => "required|string",
            "nisn" => "nullable|string|max:20"
        ]);

        $school = School::where('referral_code', $validated['referral_code'])->where('is_active', 1)->first();

        if (!$school) {
            return response()->json([
                "message" => "Kode referral tidak valid atau sekolah tidak aktif"
            ], 422);
        }

        $user = User::create([
            "school_id" => $school->id,
            "name" => $validated['name'],
            "nisn" => $validated['nisn'],
            "email" => $validated['email'],
            "password" => bcrypt($validated['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "message" => "Registrasi Berhasil",
            "data" => [
                "token" => $token,
                "user" => [
                    "name" => $user->name,
                    "nisn" => $user->nisn,
                    "email" => $user->email,
                ]
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);

        $user = User::with('school')->where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                "message" => "email atau password salah"
            ], 401);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                "message" => "email atau password salah"
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "message" => "Login berhasil",
            "data" => [
                "token" => $token,
                "user" => [
                    "id" => $user->id,
                    "school_id" => $user->school_id,
                    "email" => $user->email,
                    "role" => $user->role,
                    "nisn" => $user->nisn,
                    "rfid_uid" => $user->rfid_uid,
                    "is_active" => $user->is_active,
                    "school" => [
                        "id" => $user->school->id,
                        "name" => $user->school->name,
                        "referral_code" => $user->school->referral_code,
                    ]
                ]
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logout berhasil"
        ], 200);
    }

    public function me(Request $request)
    {
        $user = User::with('school')->find($request->user()->id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            "data" => [
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->role,
                "school" => [
                    "id" => $user->school->id,
                    "name" => $user->school->name,
                    "referral_code" => $user->school->referral_code,
                    "kiosk_pin" => $user->school->kiosk_pin,
                    "kiosk_enabled" => $user->school->kiosk_enabled,
                ],
            ]
        ], 200);
    }
}
