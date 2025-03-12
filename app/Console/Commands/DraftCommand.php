<?php

namespace App\Console\Commands;

use App\Http\Controllers\DraftController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class DraftCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draft';

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
        try {
            $controller = new DraftController();

            $draft = ($controller)(new Request(
                ['teams' => [1,2]]
            ));
        }catch (\Throwable $th){
            dd($th);
        }

    }
}
