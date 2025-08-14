<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-key {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new API key for Job Portal API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? 'client';
        $apiKey = $name . '-' . Str::random(32);

        $this->info('🔑 New API Key Generated:');
        $this->line('');
        $this->line("API Key: {$apiKey}");
        $this->line('');
        $this->info('📋 Usage:');
        $this->line('Header: X-API-Key: ' . $apiKey);
        $this->line('Query: ?api_key=' . $apiKey);
        $this->line('');
        $this->info('🔧 Add to .env file:');
        $this->line('API_KEYS=existing-keys,' . $apiKey);
        $this->line('');
        $this->warn('⚠️  Keep this key secure and don\'t share it publicly!');

        return 0;
    }
}
