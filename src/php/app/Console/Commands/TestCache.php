<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * Class TestCache
 *
 * Usage
 * php artisan test:cache --operation=get --cache_name=profile
 *
 * @package App\Console\Commands
 */
class TestCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cache
    {--operation=}
    {--cache_name=}
    ';

    /**
     * @var string
     */
    protected $operation = '';

    /**
     * @var string
     */
    protected $cache_name = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To test that application CACHE works fine or not';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->operation = $this->option('operation');
        $this->cache_name = $this->option('cache_name');

        if (empty($this->operation)) {
            $this->error('Operation is required i.e --operation');
            return;
        } else if ( !($this->operation == 'get' || $this->operation == 'put' || $this->operation == 'forget') ) {
            $this->error('Operation value should be [get or put or forget] ');
            return;
        }

        if (empty($this->cache_name)) {
            $this->error('Cache name is required i.e --cache_name=');
            return;
        }


        switch ($this->operation) {
            case 'get':
                $cache = Cache::get($this->cache_name);
                $this->info('Cache value is '. $cache);
                break;
            case 'put':
                Cache::put($this->cache_name, 'OK');
                $this->info('Cache value is OK');
                break;
            case 'forget':
                Cache::forget($this->cache_name);
                $this->info('Cache value is EMPTY');
                break;
        }

        return Command::SUCCESS;
    }
}
