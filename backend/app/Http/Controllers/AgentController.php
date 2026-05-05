<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AdminAgent;
use App\Ai\Agents\LibrarianAgent;
use Illuminate\Http\Request;

class AgentController
{
    public function studentAgent(Request $request)
    {
        try {
            $response = (new LibrarianAgent)->prompt((string) $request->input('message'));

            return response()->json([
                'status' => 'success',
                'ai_response' => (string) $response,
            ]);

        } catch (\Exception $e) {
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
        try {
            $response = (new AdminAgent)->prompt((string) $request->input('message'));

            return response()->json([
                'status' => 'success',
                'ai_response' => (string) $response,
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
