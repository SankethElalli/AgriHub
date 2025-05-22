<?php
class YieldPredictionAPI {
    private $apiKey;
    private $apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct($apiKey = '') // Set your API key here
    {
        $this->apiKey = $apiKey;
    }

    public function getYieldPrediction($state, $district, $season, $crop, $area) {
        $prompt = $this->buildPrompt($state, $district, $season, $crop, $area);
        $response = $this->makeApiCall($prompt);
        return $this->parseResponse($response);
    }

    private function buildPrompt($state, $district, $season, $crop, $area) {
        return "As an agricultural expert system, predict the crop yield for these parameters:\n" .
               "- State: {$state}\n" .
               "- District: {$district}\n" .
               "- Season: {$season}\n" .
               "- Crop: {$crop}\n" .
               "- Area: {$area} hectares\n\n" .
               "Provide:\n" .
               "1. The predicted yield in quintals\n" .
               "2. A brief explanation of the prediction\n" .
               "Format the response exactly as: Yield: XX.XX quintals - Explanation";
    }

    private function makeApiCall($prompt) {
        try {
            $data = [
                'model' => 'deepseek/deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an agricultural yield prediction expert. Provide yield predictions in quintals based on the given parameters.'
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
        
        // Extract yield value and explanation
        if (preg_match('/Yield:\s*(\d+\.?\d*)\s*quintals\s*-\s*(.*)/', $content, $matches)) {
            return [
                'yield' => floatval($matches[1]),
                'explanation' => trim($matches[2])
            ];
        }

        return ['error' => 'Could not parse prediction from response'];
    }
}
?>
