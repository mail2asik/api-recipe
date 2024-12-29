<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis Test channel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Redis::subscribe(['test-redis-channel'], function ($message) {
            echo $message;
        });
    }
}
