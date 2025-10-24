<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GlpiService;

class GlpiTestConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glpi:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the connection to the GLPI API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(GlpiService $glpiService)
    {
        $this->info('Testing GLPI Connection...');

        $this->line('Attempting to initialize session...');
        $sessionToken = $glpiService->initSession();

        if (empty($sessionToken)) {
            $this->error('Failed to initialize GLPI session. Please check your logs for more details.');
            $this->line('Check your GLPI_API_URL, GLPI_APP_TOKEN, and GLPI_API_TOKEN in your .env file.');
            return 1;
        }

        $this->info('Session initialized successfully!');
        $this->line('Session Token: ' . $sessionToken);

        $this->info('GLPI connection test successful!');

        return 0;
    }
}
