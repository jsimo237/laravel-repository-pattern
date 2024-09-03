<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

class CreateRepositoryCommand extends GeneratorCommand{

    use HasStub;

    public $signature = "make:repository {name}";

    public $description = "Create a new repository class";

    private $className = null;

    const MODELS_NAMESPACE = "App\\Models";
    const RESOURCES_NAMESPACE = "App\\Http\\Resources";
    const VAlIDATOR_NAMESPACE = "App\\Http\\Requests";


    /**
     * Execute the console comma nd.
     *
     * @return mixed
     */
    public function handle(){


        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->components->error('The name "'.$this->getNameInput().'" is reserved by PHP.');

            return false;
        }


//        if (parent::handle() === false && ! $this->option('force')) {
//            return false;
//        }

        //dd($this->getDefaultNamespace($this->rootNamespace()));

        $name = Str::studly(class_basename($this->getNameInput()));
       // $name = $this->qualifyClass($name);


        $inputs = [];
        $inputs['model'] = $this->ask('Path or Name of eloquent model filename',$name);
        $inputs['resource'] = $this->ask('Path or Name of Http Resource filename',$name);
        $inputs['controller'] = $this->ask('Path or Name of Http Controller filename',$name);
        $inputs['route'] = $this->ask('Path or Name of route filename',strtolower($name));


        $this->getRepositoryFileContent($inputs);



//        if ($inputs['model']) {
//            $this->call("make:repository.model",['name' =>  $inputs['model']]);
//        }
//
//        if ($inputs['resource']) {
//            $this->call("make:repository.resource",['name' =>  $inputs['resource']]);
//        }
//
//        if ($inputs['controller']) {
//            $this->call("make:repository.controller",['name' =>  $inputs['controller']]);
//        }
//
//        if ($inputs['route']) {
//            $this->call("make:repository.route",['name' =>  $inputs['route']]);
//        }
    }



    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace){
        //dd($rootNamespace);
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

    protected function generateFile($name){


        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists(trim($name))) {
            $this->components->error($this->type.' already exists.');

            return false;
        }

    }


    /**
     * Le contenu par défaut du repository qui sera généré
     *
     * @param $inputs
     * @return bool|mixed|string
     * @throws FileNotFoundException
     */
    public function getRepositoryFileContent($inputs){

        $file_path = $this->qualifyClass($this->getNameInput(),"Repository");

        $class_name = Str::studly(class_basename($file_path));


        $path = $this->getPath($file_path);


        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->components->error($this->type.' already exists.');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $model = $inputs['model'] ; // User
        $resource = $inputs['resource'] ; // User
        $validator = $inputs['validator'] ?? "Papa" ; // User

        $model_name = Str::studly(class_basename($model));
        $resource_name = Str::studly(class_basename($resource));
        $validator_name = Str::studly(class_basename($validator));


        $resource_name_suffixe = Str::substr($resource_name,-8,8); // les 08 derniers caractères


        if (strtolower($resource_name_suffixe) !== "resource") $resource_name .="Resource";


        $namespace = $this->getNamespace($this->qualifyClass($this->getNameInput()));
        $class = $this->qualifyClass($this->getNameInput());
        $model    = $this->qualifyClass($inputs['model']); // User

        $namespacedModel = $this->getNamespace($this->qualifyClass($model)); // App\\Models\\User
        $namespacedResource = $this->getNamespace($resource); // App\\Http\\Resources\\UserResource



        $stubVariables = [
            'namespace'            => $namespace,
            'class'                => $class_name,
            'class1'                => $class,
            'model'                => "$model_name::class",
            'resource'             => "$resource_name::class",
           // 'validator'            => $validator,

//            'namespacedModel'      => "use $namespacedModel;",
//            'namespacedResource'   => "use $namespacedResource;",
//            'namespacedValidator'   => "",
        ];

      //  dd($stubVariables,$path,$model);



       $content = $this->getStubContents( $this->getStubPath("repository.stub"), $stubVariables);


        $this->files->put($path, $content);

        $this->call('make:repository.model',
                    [
                        'name' => $model,
                        '--repository' => $path
                    ]
                );

        $info = $this->type;

        if (windows_os()) {
            $path = str_replace('/', '\\', $path);
        }

        $this->components->info(sprintf('%s [%s] created successfully.', $info, $path));



    }

    protected function createModelFile(){}
    protected function createResourceFile(){}
    protected function createControllerFile(){}
    protected function createRouteFile(){}


    /**
     * Le contenu par défaut du repository qui sera généré
     *
     * @return bool|mixed|string
     */
    protected function getModelFileContent(){
        $stubVariables = [
            'NAMESPACE'        => "App\\Models",
            'CLASS_NAME'       => null,
        ];
        return $this->getStubContents( $this->getStubPath("model.stub"), $stubVariables);
    }


    /**
     * Le contenu par défaut du repository qui sera généré
     *
     * @return bool|mixed|string
     */
    protected function getResourceFileContent(){
        $stubVariables = [
            'NAMESPACE'        => "App\\Http\\Resources",
            'CLASS_NAME'       => null,
        ];
        return $this->getStubContents( $this->getStubPath("resource.stub"), $stubVariables);
    }

    /**
     * Le contenu par défaut du repository qui sera généré
     *
     * @return bool|mixed|string
     */
    protected function getControllerFileContent(){
        $stubVariables = [
            'NAMESPACE'        => "App\\Http\\Controllers",
            'CLASS_NAME'       => null,
        ];
        return $this->getStubContents( $this->getStubPath("controller.stub"), $stubVariables);
    }




    protected function getStub(){
       return __DIR__ . "/stubs/repository.stub";
    }
}