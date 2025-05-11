<?php
class CropPredictionAPI {
    private $apiKey;
    private $apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct($apiKey = '') //Your API KEY 
    {
        $this->apiKey = $apiKey;
    }

    public function getCropPrediction($state, $district, $season) {
        try {
            $prompt = $this->buildPrompt($state, $district, $season);
            $response = $this->makeApiCall($prompt);
            return $this->parseResponse($response);
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            return ['error' => 'API request failed: ' . $e->getMessage()];
        }
    }

    private function buildPrompt($state, $district, $season) {
        return "As an agricultural expert system, recommend crops for:\n" .
               "Location: {$district}, {$state}, India\n" .
               "Growing Season: {$season}\n\n" .
               "List only the names of 10 most suitable crops as a comma-separated list, based on:\n" .
               "1. Historical crop success in this region\n" .
               "2. Local climate conditions\n" .
               "3. Typical soil characteristics\n" .
               "4. Water availability\n" .
               "5. Market demand\n\n" .
               "Response format: crop1, crop2, crop3, crop4, crop5, crop6, crop7, crop8, crop9, crop10";
    }

    private function makeApiCall($prompt) {
        $data = [
            'model' => 'deepseek/deepseek-chat',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 250,  // Increased to accommodate more crops
            'top_p' => 0.9
        ];

        $ch = curl_init($this->apiEndpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'HTTP-Referer: http://localhost:3000'
            ],
            CURLOPT_SSL_VERIFYPEER => false // Only for development
        ]);

        $response = curl_exec($ch);
        
        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP error: $httpCode - $response");
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response');
        }

        return $decoded;
    }

    private function parseResponse($response) {
        if (!isset($response['choices'][0]['message']['content'])) {
            error_log("API Response: " . print_r($response, true));
            return ['error' => 'Unexpected API response format'];
        }

        $content = trim($response['choices'][0]['message']['content']);
        if (empty($content)) {
            return ['error' => 'Empty response from API'];
        }

        // Clean up the response and split into array
        $crops = array_map('trim', explode(',', $content));
        $crops = array_filter($crops); // Remove empty values

        if (empty($crops)) {
            return ['error' => 'No valid crops in response'];
        }

        return $crops;
    }
}
?>
