<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

/**
 * php artisan generate:token
 */
class GenerateTokenCommand extends Command
{
    private const RANDOM_BYTES_LENGTH = 16;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate token for API authorization';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle()
    {
        $token = bin2hex(random_bytes(self::RANDOM_BYTES_LENGTH));
        echo 'Token has been successfully generated: '.$token.PHP_EOL;
    }
}
