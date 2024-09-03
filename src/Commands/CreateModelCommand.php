<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

class CreateModelCommand extends GeneratorCommand{

    use HasStub;

    public $signature = "make:repository.model {name} {--repository=}";

    public $description = "Create a new eloquent model class";

    const NAMESPACE = "App\\Models";


    /**
     * Execute the console comma nd.
     *
     * @return mixed
     */
    public function handle(){


        $name = Str::studly(class_basename($this->getNameInput()));

        $namespacedModel = $this->getNamespace($this->getNameInput());

        if ($this->option("repository")){
            $repository_path = $this->option("repository");
            $stubVariables = [
                'namespacedModel'      => "use $namespacedModel;",
            ];


            $content = $this->getStubContents($repository_path, $stubVariables);

            $this->files->put($repository_path, $content);
        }

    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace){
        return $rootNamespace.'\\Models';
    }



    protected function getStub(){
       return __DIR__ . "/stubs/model.stub";
    }
}