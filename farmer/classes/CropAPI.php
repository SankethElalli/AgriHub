<?php
class DeepseekAPI {
    private $apiKey;
    private $apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct($apiKey = '') // Set your API key here
    {
        $this->apiKey = $apiKey;
    }

    public function getCropPrediction($params) {
        $prompt = $this->buildPrompt($params);
        $response = $this->makeApiCall($prompt);
        return $this->parseResponse($response);
    }

    private function buildPrompt($params) {
        return "As an agricultural expert system, analyze these soil and climate conditions:\n" .
               "- Nitrogen: {$params['n']} mg/kg\n" .
               "- Phosphorus: {$params['p']} mg/kg\n" .
               "- Potassium: {$params['k']} mg/kg\n" .
               "- Temperature: {$params['t']}°C\n" .
               "- Humidity: {$params['h']}%\n" .
               "- pH: {$params['ph']}\n" .
               "- Rainfall: {$params['r']} mm\n\n" .
               "Based on these parameters, recommend suitable crops. For each crop, provide:\n" .
               "1. A confidence score (0-100%)\n" .
               "2. Brief explanation why it's suitable considering the soil nutrients and climate conditions\n" .
               "Format each recommendation exactly as: CropName: XX% - Explanation";
    }

    private function makeApiCall($prompt) {
        try {
            $data = [
                'model' => 'deepseek/deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an agricultural expert system. Respond with crop recommendations in the format: CropName: XX% - Explanation'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
                'safe_mode' => false,
                'stream' => false
            ];

            $ch = curl_init($this->apiEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
            $result = json_decode($response, true);
            
            if (isset($result['error'])) {
                throw new Exception($result['error']['message'] ?? 'Unknown API error');
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("DeepSeek API Error: " . $e->getMessage());
            return ['error' => "API Error: " . $e->getMessage()];
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
                    'crop' => trim($matches[1]),
                    'confidence' => intval($matches[2]),
                    'explanation' => trim($matches[3])
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