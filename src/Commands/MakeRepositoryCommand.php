<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends GeneratorCommand {

    use HasStub;

    public $signature = "make:repository {name} {--model=} {--resource=} {--validator=}";

    public $description = "Create a new repository class";

    private $config = null;

    const MODELS_NAMESPACE = "App\\Models";
    const RESOURCES_NAMESPACE = "App\\Http\\Resources";
    const VAlIDATOR_NAMESPACE = "App\\Http\\Requests";

    const NAMESPACE = "\\Reposito";


    /**
     * Execute the console comma nd.
     *
     * @return mixed
     */
    public function handle(){

        $this->config =  config("repository-pattern");

        $this->checkIsReserved();

        $this->generateFileContent();


//        if (parent::handle() === false && ! $this->option('force')) {
//            return false;
//        }

        //dd($this->getDefaultNamespace($this->rootNamespace()));

        //$name = Str::studly(class_basename($this->getNameInput()));
       // $name = $this->qualifyClass($name);
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace){
        return $rootNamespace.'\\Repositories';
    }


    /**
     * Retourne le chemin d’accès du fichier stub
     * @param string $file
     * @return string
     */
    public function getStubPath($file){
        return __DIR__ . "/stubs/$file";
    }

    /**
     * Le contenu par défaut du repository qui sera généré
     * @return bool|mixed|string
     */
    public function generateFileContent(){

        $config = $this->config;

        //dd($config);

        $model = $this->hasOption("model") ? $this->option('model') : null;
        $resource = $this->hasOption("resource") ? $this->option('resource') : null;
        $validator = $this->hasOption("validator") ? $this->option('validator') : null;

        $dependencies = "";
        $dependenciesNamespaces = "";

        // name = UserManagement\User

        $file_path = $this->qualifyClass($this->getNameInput(),"Repository"); // App\Repositories\UserManagement\UserRepository

        $class_name = Str::studly(class_basename($file_path)); // UserRepository

        $path = $this->getPath($file_path); // App\Repositories\UserManagement\UserRepository.php

        if($this->checkAlreadyExist($path)) return ;

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        if ($model){
            $model_filename = Str::studly(class_basename($model));
            $model_namespace = $config['models_namespace']."\\".$model; // App\\Models\\User

            $this->call("make:model",['name' => $model]);
            $dependenciesNamespaces = "use $model_namespace; \n";
            $dependencies = "$model_filename::class";
         //   $file_path = $this->getPath($model_nampespace);

        }
        if ($resource){
            $resource_filename = Str::studly(class_basename($resource));
            $resource_namespace = $config['resources_namespace']."\\".$resource; // App\\Http\\Resources\\User

            $suffix = "Resource";
            if (!str_contains($resource_filename,$suffix)){
                $resource_filename .= $suffix;
                $resource_namespace .= " as $resource_filename";
            }

            $this->call("make:resource",['name' => $resource]);
            $dependenciesNamespaces .= "use $resource_namespace; \n";
            $dependencies .= ", $resource_filename::class";
        }
        if ($validator){
            $validator_filename = Str::studly(class_basename($validator));
            $validator_namespace = $config['validators_namespace']."\\".$validator; // App\\Http\\Resources\\User
            $suffix = "Request";
            if (!str_contains($validator_filename,$suffix)){
                $validator_filename  .= $suffix;
                $validator_filename .= " as $validator_filename ";
            }
            $this->call("make:request",['name' => $validator]);
            $dependenciesNamespaces = "use $validator_namespace;";
            $dependencies .= ", $validator_filename::class";
        }

     //   $resource_name_suffixe = Str::substr($resource_name,-8,8); // les 08 derniers caractères


      //  if (strtolower($resource_name_suffixe) !== "resource") $resource_name .="Resource";


        $namespace = $this->getNamespace($this->qualifyClass($this->getNameInput()));
        //$class = $this->qualifyClass($this->getNameInput());

        //dd($class_name,$file_path,$path,$namespace);

        $stubVariables = [
            'namespace'            => $namespace,
            'class'                => $class_name,
            'dependencies'         => $dependencies,
            'dependenciesNamespaces' => $dependenciesNamespaces,
        ];

        $stub = ($model or $resource or $validator) ? "repository.stub" : "basic-repository.stub";

        $content = $this->getStubContents( $this->getStubPath($stub), $stubVariables);

        $this->files->put($path, $content);

        $this->createController([
                $class_name,
                $namespace."\\".$class_name
            ]);

        $info = $this->type;

        if (windows_os()) {
            $path = str_replace('/', '\\', $path);
        }

        $this->components->info(sprintf('%s Repository [%s] created successfully.', $info, $path));

    }


    protected function createController($repository){

        $config = $this->config;

        if ($this->confirm("Do you want to create controller ?")){

            $controller = $this->ask("Enter controller class name");

            $controller_filename = Str::studly(class_basename($controller));

            $namespace = $config['controllers_namespace']."\\".$controller; // App\\Http\\Controller\\User
            $suffix = "Controller";
            if (!str_contains($controller_filename,$suffix)){
                $controller_filename  .= $suffix;
              //  $validator_filename .= " as $validator_filename ";
            }

            $path = '';
            $this->makeDirectory($path);

            [$repositoryFileName, $repositoryNamespace] = $repository;

            $repositoryFileName = "new $repositoryFileName";
            $repositoryNamespace = "use $repositoryNamespace;";


            $stubVariables = [
                'namespace'            => $namespace,
            'class'                  => $controller_filename,
                'repository'         => $repositoryFileName,
                'repositoryNamespace' => $repositoryNamespace,
            ];

            $content = $this->getStubContents( $this->getStubPath("controller.stub"), $stubVariables);

            $this->files->put($path, $content);

            $info = $this->type;
            if (windows_os()) {
                $path = str_replace('/', '\\', $path);
            }

            $this->components->info(sprintf('%s Controller [%s] created successfully.', $info, $path));
        }
    }


    protected function getStub(){
       return __DIR__ . "/stubs/repository.stub";
    }
}