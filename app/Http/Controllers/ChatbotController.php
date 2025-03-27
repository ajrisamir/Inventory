<?php

namespace App\Http\Controllers;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Purchase;

class ChatbotController extends Controller
{
    public function showChat()
    {
        return view('chatbot.chat');
    }

    public function processMessage(Request $request)
    {
        try {
            $message = $request->input('message');
            $type = $request->input('type');

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI assistant managing an inventory system.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ]
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $response->choices[0]->message->content
            ]);
        } catch (\Exception $e) {
            \Log::error('Chatbot Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    private function processAIResponse($response)
    {
        // Implementasi logika untuk memproses berbagai perintah
        // Contoh: tambah produk, generate laporan, dll
        return [
            'message' => $response,
            'data' => null
        ];
    }

    public function processVoice(Request $request)
    {
        $audioFile = $request->file('audio');
        // Proses audio ke teks menggunakan Google Speech-to-Text
        // Implementasi konversi audio
    }
}