<?php


namespace Jsimo237\LaravelRepositoryPattern\Commands;

use Illuminate\Console\Command;

class CreateResourceCommand extends Command{

    public $signature = "spx:command-name";

    public $desription = "My Spx Command";


    public function handle(){
        $this->comment('All Done.');


        return self::SUCCESS;
    }

}