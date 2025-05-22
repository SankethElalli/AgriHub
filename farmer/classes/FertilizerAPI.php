<?php
class FertilizerAPI {
    private $apiKey;
    private $apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct($apiKey = '') //Your API KEY 
    {
        $this->apiKey = $apiKey ?: getenv('OPENROUTER_API_KEY');
        if (!$this->apiKey) {
            throw new Exception('API key not set. Please set OPENROUTER_API_KEY in your environment.');
        }
    }

    public function getFertilizerPrediction($params) {
        $prompt = $this->buildPrompt($params);
        $response = $this->makeApiCall($prompt);
        return $this->parseResponse($response);
    }

    private function buildPrompt($params) {
        return "As an agricultural expert system, analyze these soil conditions:\n" .
               "- Nitrogen content: {$params['n']} mg/kg\n" .
               "- Phosphorus content: {$params['p']} mg/kg\n" .
               "- Potassium content: {$params['k']} mg/kg\n" .
               "- Temperature: {$params['t']}°C\n" .
               "- Humidity: {$params['h']}%\n" .
               "- Soil Moisture: {$params['sm']}%\n" .
               "- Soil Type: {$params['soil']}\n\n" .
               "Based on these soil conditions and nutrient levels:\n" .
               "1. Analyze current nutrient balance and deficiencies\n" .
               "2. Consider soil type characteristics\n" .
               "3. Account for moisture and environmental conditions\n\n" .
               "Recommend suitable fertilizers, including:\n" .
               "- The type of fertilizer\n" .
               "- NPK ratio benefits\n" .
               "- Application guidelines\n" .
               "- Suitability score\n\n" .
               "Format each recommendation as: FertilizerName: XX% - [Details about NPK benefits, application rate, and timing]";
    }

    private function makeApiCall($prompt) {
        try {
            $data = [
                'model' => 'deepseek/deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an agricultural expert system. Provide recommendations in clear, concise format.'
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: https://agrihub.com',  // Required by OpenRouter
                'X-Title: AgriHub'  // Required by OpenRouter
            ]);

            $response = curl_exec($ch);
            
            if ($error = curl_error($ch)) {
                throw new Exception("API call failed: " . $error);
            }
            
            curl_close($ch);

            error_log("API Response: " . $response); // Debug logging
            
            $result = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("JSON decode error: " . json_last_error_msg());
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    private function parseResponse($response) {
        if (!isset($response['choices'][0]['message']['content'])) {
            error_log("API Response: " . print_r($response, true));
            return ['error' => 'Invalid API response'];
        }

        $content = $response['choices'][0]['message']['content'];
        
        // Process the response to extract structured data
        $predictions = $this->extractPredictions($content);
        
        return $predictions;
    }

    private function extractPredictions($content) {
        // Parse the AI response into structured data
        $lines = explode("\n", $content);
        $predictions = [];
        
        foreach ($lines as $line) {
            if (preg_match('/^([^:]+):\s*(\d+)%\s*-\s*(.*)$/', $line, $matches)) {
                $predictions[] = [
                    'fertilizer' => trim($matches[1]),
                    'confidence' => intval($matches[2]),
                    'details' => trim($matches[3])
                ];
            }
        }
        
        return $predictions;
    }

    public function testConnection() {
        try {
            $data = [
                'model' => 'deepseek/deepseek-chat',  // Corrected model name
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a test system.'
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Say "API test successful"'
                    ]
                ],
                'max_tokens' => 50
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
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            
            if ($error = curl_error($ch)) {
                throw new Exception("API call failed: " . $error);
            }
            
            curl_close($ch);
            $result = json_decode($response, true);
            
            if (isset($result['error'])) {
                throw new Exception($result['error']['message'] ?? 'Unknown API error');
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("DeepSeek API Test Error: " . $e->getMessage());
            return ['error' => "API Test Error: " . $e->getMessage()];
        }
    }
}
?>