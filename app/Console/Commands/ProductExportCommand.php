<?php

namespace App\Console\Commands;

use App\Jobs\ProcessProducts;
use Illuminate\Console\Command;

class ProductExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments  = $this->arguments();
        $options    = $this->options();
        ProcessProducts::dispatch($options)
            ->onConnection('redis')
            ->onQueue('products');
    }
}
