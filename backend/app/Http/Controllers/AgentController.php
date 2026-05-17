<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AdminAgent;
use App\Ai\Agents\LibrarianAgent;
use Illuminate\Http\Request;

class AgentController
{
    public function studentAgent(Request $request)
    {
        set_time_limit(120);
        try {
            $response = (new LibrarianAgent)->prompt((string) $request->input('message'));

            return response()->json([
                'status' => 'success',
                'ai_response' => (string) $response,
            ]);

        } catch (\Exception $e) {
            $msg = strtolower($e->getMessage());
            if (strpos($msg, '429') !== false || strpos($msg, 'rate limit') !== false || strpos($msg, 'exhausted') !== false) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Layanan AI sedang sibuk atau batas penggunaan harian telah tercapai. Harap coba lagi dalam beberapa saat.'
                ], 429);
            }
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    
    public function adminAgent(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden access',
            ], 403);
        }
        
        set_time_limit(120);
        
        try {
            $response = (new AdminAgent)->prompt((string) $request->input('message'));

            return response()->json([
                'status' => 'success',
                'ai_response' => (string) $response,
            ]);

        } catch (\Exception $e) {
            $msg = strtolower($e->getMessage());
            if (strpos($msg, '429') !== false || strpos($msg, 'rate limit') !== false || strpos($msg, 'exhausted') !== false) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Layanan AI sedang sibuk atau batas penggunaan harian telah tercapai. Harap coba lagi dalam beberapa saat.'
                ], 429);
            }
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
