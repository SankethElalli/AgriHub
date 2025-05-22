<?php
class RainfallPredictionAPI {
    private $apiKey;
    private $apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct($apiKey = '') // Set your API key here
    {
        $this->apiKey = $apiKey;
    }

    public function getRainfallPrediction($region, $month) {
        $prompt = $this->buildPrompt($region, $month);
        $response = $this->makeApiCall($prompt);
        return $this->parseResponse($response);
    }

    private function buildPrompt($region, $month) {
        return "As an agricultural meteorology expert system, predict the rainfall for:\n" .
               "- Region: {$region}\n" .
               "- Month: {$month}\n\n" .
               "Provide:\n" .
               "1. The predicted rainfall in millimeters (mm)\n" .
               "2. A brief explanation of the prediction considering historical patterns and regional climate\n" .
               "Format the response exactly as: Rainfall: XX.XX mm - Explanation";
    }

    private function makeApiCall($prompt) {
        try {
            $data = [
                'model' => 'deepseek/deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an agricultural meteorology expert. Provide rainfall predictions in millimeters (mm) based on historical patterns and regional climate data.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ];

            $ch = curl_init($this->apiEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: https://agrihub.com',
                'X-Title: AgriHub'
            ]);

            $response = curl_exec($ch);
            
            if ($error = curl_error($ch)) {
                throw new Exception("API call failed: " . $error);
            }
            
            curl_close($ch);
            return json_decode($response, true);

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function parseResponse($response) {
        if (isset($response['error'])) {
            return $response;
        }

        if (!isset($response['choices'][0]['message']['content'])) {
            return ['error' => 'Invalid API response'];
        }

        $content = $response['choices'][0]['message']['content'];
        
        // Extract rainfall value and explanation
        if (preg_match('/Rainfall:\s*(\d+\.?\d*)\s*mm\s*-\s*(.*)/', $content, $matches)) {
            return [
                'rainfall' => floatval($matches[1]),
                'explanation' => trim($matches[2])
            ];
        }

        return ['error' => 'Could not parse prediction from response'];
    }
}
?>
