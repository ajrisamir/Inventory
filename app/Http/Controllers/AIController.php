<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AIController extends Controller
{
    private $ollamaClient;

    public function __construct()
    {
        try {
            $this->ollamaClient = new Client([
                'base_uri' => 'http://localhost:11434/',
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'verify' => false,
                'timeout' => 30,  // Reduced timeout
                'connect_timeout' => 5  // Added connect timeout
            ]);
        } catch (\Exception $e) {
            Log::error('Ollama initialization error: ' . $e->getMessage());
        }
    }

    public function processMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string',
                'type' => 'required|string'
            ]);

            // Check if Ollama is running
            try {
                $healthCheck = $this->ollamaClient->get('', ['timeout' => 5]);
                Log::info('Ollama health check', ['status' => $healthCheck->getStatusCode()]);
            } catch (\Exception $e) {
                Log::error('Ollama not running', ['error' => $e->getMessage()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ollama service is not running. Please start Ollama first.'
                ], 503);
            }

            $payload = [
                'model' => 'tinyllama',
                'prompt' => $validated['message'],
                'stream' => false
            ];

            Log::info('Request payload', ['payload' => $payload]);
            
            $response = $this->ollamaClient->post('api/generate', [
                'json' => $payload,
                'timeout' => 30
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (!$result) {
                throw new \Exception('Invalid response from Ollama');
            }

            return response()->json([
                'status' => 'success',
                'message' => $result['response']
            ]);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::error('Connection error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to connect to Ollama. Please check if service is running.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Chat error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }
}