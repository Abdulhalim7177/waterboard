<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

// Create a simple test of the GLPI service without Laravel context
class GLPIServiceTest {
    private $apiUrl;
    private $username;
    private $password;
    private $apiToken;

    public function __construct() {
        // These would normally come from config, using hardcoded values for test
        $this->apiUrl = 'https://glpi.demo.sft.com.ng/apirest.php';
        $this->username = 'ktwaterboard_demo';
        $this->password = 'cf_tags=cf-proxied!true';
        $this->apiToken = 'zQ3fBMtYHrG2llez0DdRaKn1eWw64JD4VbyILIwv';
    }

    public function testConnection() {
        try {
            // Test GLPI API connection using API token
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/initSession', [
                'user_token' => $this->apiToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                echo "GLPI API Connection Successful!\n";
                echo "Session Token: " . $data['session_token'] . "\n";
                return $data['session_token'];
            } else {
                echo "GLPI API Connection Failed!\n";
                echo "Status: " . $response->status() . "\n";
                echo "Response: " . $response->body() . "\n";
                return false;
            }
        } catch (\Exception $e) {
            echo "Exception occurred: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Run the test
echo "Testing GLPI API Connection...\n";
$test = new GLPIServiceTest();
$sessionToken = $test->testConnection();

if ($sessionToken) {
    echo "Successfully connected to GLPI API!\n";
} else {
    echo "Failed to connect to GLPI API.\n";
}