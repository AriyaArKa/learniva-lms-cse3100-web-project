<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        // Using Gemini 2.0 Flash (experimental) - working model
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent';
    }

    /**
     * Generate a response from Gemini AI
     *
     * @param string $prompt The user's message
     * @return string The AI's response
     */
    public function generateResponse($prompt)
    {
        try {
            $url = $this->apiUrl . '?key=' . $this->apiKey;

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ];

            Log::info('Gemini API Request', [
                'url' => $url,
                'prompt' => $prompt
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            Log::info('Gemini API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Extract the generated text from the response
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }

                Log::warning('Gemini API returned success but unexpected format', ['data' => $data]);
                return "I'm sorry, I couldn't generate a response at this time.";
            }

            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return "I'm sorry, I encountered an error while processing your request. (Status: " . $response->status() . ")";

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return "I'm sorry, I'm having trouble connecting to the AI service right now. Error: " . $e->getMessage();
        }
    }

    /**
     * Check if a message is intended for the AI assistant
     *
     * @param string $message
     * @return bool
     */
    public function isAiMessage($message)
    {
        return stripos(trim($message), '@gpt') === 0;
    }

    /**
     * Extract the actual prompt from the message (remove @gpt prefix)
     *
     * @param string $message
     * @return string
     */
    public function extractPrompt($message)
    {
        return trim(preg_replace('/^@gpt\s*/i', '', $message));
    }
}
