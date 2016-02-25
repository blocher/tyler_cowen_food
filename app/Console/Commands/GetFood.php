<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrapers;

class getFood extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:food';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the food information';

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
        $foodScraper = new \App\Scrapers\FoodScraper(new \App\Scrapers\CurlScraper());
        echo $foodScraper->go();
        echo 'Finished';
    }
}
