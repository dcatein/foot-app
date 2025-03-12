<?php

namespace App\Console\Commands;

use App\Http\Controllers\MatchController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class MatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new MatchController();


        $match = ($controller)(
            new Request([
                'home' => 1,
                'away' => 2
            ])
        );
    }
}
