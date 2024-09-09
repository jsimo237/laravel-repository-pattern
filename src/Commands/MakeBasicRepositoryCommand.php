<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeBasicRepositoryCommand extends GeneratorCommand {

    use HasStub;

    public $signature = "repository:create.basic {name}";

    public $description = "Create a new basic repository class";

    const NAMESPACE = "\\Repositories";

    /**
     * Execute the console comma nd.
     *
     * @return mixed
     */
    public function handle(){

        $this->checkIsReserved();

        $this->generateFileContent();
    }



    /**
     * Le contenu par défaut du repository qui sera généré
     * @return bool|mixed|string
     */
    public function generateFileContent(){

        // name = UserManagement\User

        $file_path = $this->qualifyClass($this->getNameInput(),"Repository"); // App\Repositories\UserManagement\UserRepository

        $class_name = Str::studly(class_basename($file_path)); // UserRepository

        $path = $this->getPath($file_path); // App\Repositories\UserManagement\UserRepository.php

        if($this->checkAlreadyExist($path)) return ;

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $namespace = $this->getNamespace($this->qualifyClass($this->getNameInput()));

        //dd($class_name,$file_path,$path,$namespace);

        $stubVariables = [
            'namespace'            => $namespace,
            'class'                => $class_name,
        ];

       $content = $this->getStubContents( $this->getStubPath("basic-repositoy.stub"), $stubVariables);

       $this->files->put($path, $content);

        $info = $this->type;

        if (windows_os()) {
            $path = str_replace('/', '\\', $path);
        }

        $this->components->info(sprintf('%s [%s] created successfully.', $info, $path));

    }


    protected function getStub(){
       return __DIR__ . "/stubs/basic-repositoy.stub";
    }

}