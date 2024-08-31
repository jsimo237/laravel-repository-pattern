<?php


namespace Jsimo237\LaravelRepositoryPattern\Commands;

use Illuminate\Console\Command;

class CreateRepositoryCommand extends Command{

    public $signature = "make:repository";

    public $desription = "My Spx Command";


    public function handle(){
        $this->comment('All Done.');


        return self::SUCCESS;
    }

}