<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;

use Illuminate\Console\Command;

class CreateModelCommand extends Command{

    public $signature = "make:repository";

    public $desription = "My Spx Command";


    public function handle(){
        $this->comment('All Done.');


        return self::SUCCESS;
    }

}