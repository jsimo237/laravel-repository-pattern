<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Pluralizer;

class CreateRepositoryCommand extends Command{

    public $signature = "make:repository {name}";

    public $desription = "My Spx Command";


    protected $namespace = "App\\Repositories";



    /**
     * Execute the console comma nd.
     *
     * @return mixed
     */
    public function handle(){

        $inputs = [];
        $inputs['model'] = $this->ask('Path or Name of Eloquent model filename');
        $inputs['resource'] = $this->ask('Path or Name of Http Resource filename');
        $inputs['controller'] = $this->ask('Path or Name of Http Controller filename',null);
        $inputs['route_file'] = $this->ask('Path or Name of route filename',null);

        $repositoryFilePath = $this->getRepositoriesFilePath();
        $modelFilePath = $this->getRepositoriesFilePath();
        $resourceFilePath = $this->getRepositoriesFilePath();

        $this->makeDirectory(dirname($repositoryFilePath));


        if (!$this->files->exists($modelFilePath)) {
            $this->createModelFile($modelFilePath);
            $this->info("[model-file] : {$modelFilePath} => created");
        }


        if (!$this->files->exists($resourceFilePath)) {
            $this->createResourceFile($resourceFilePath);
            $this->info("[resource-file] : {$resourceFilePath} => created");
        }

        if ($inputs['controller']){
            if (!$this->files->exists($modelFilePath)) {
                $this->createControllerFile($modelFilePath);
                $this->info("[controller-file] : {$modelFilePath} => created");
            }
        }
        if ($inputs['route_file']){
            if (!$this->files->exists($modelFilePath)) {
                $this->createRouteFile($modelFilePath);
                $this->info("[route-file] : {$modelFilePath} => created");
            }
        }

        if (!$this->files->exists($repositoryFilePath)) {
            $this->createResourceFile($repositoryFilePath);
            $this->info("[repository-file] : {$repositoryFilePath} => created");
        }

        $this->files->put($repositoryFilePath, $this->getRepositoryFileContent());
    }


    /**
     * Retourne le chemin d’accès du fichier stub
     * @param string $file
     * @return string
     */
    public function getStubPath($file){
        return __DIR__ . "../../stubs/repository-pattern/$file";
    }

    /**
     * Formate le nom du fichier en un nom utilisé pour la création de fichier
     * @return string
     */
    protected function getRealFileName(){
        $name = $this->argument('name');

        // Ex : $name = "UserRepository"

        if (str_contains("Repository",$name)){

        }

        $name = str_replace([' ','-'],'_',$name);
        $wordsOfActionPage = explode("_",$name);

        if ($wordsOfActionPage){
            /**
            array:4 [
            0 => "Transmgt"
            1 => "Differed"
            2 => "Collections"
            3 => "View"
            ]
             */
            $wordsTruncated =  array_map(fn($word)=>ucfirst($word),$wordsOfActionPage);


            /**  "UserRepository" */
            return implode("",$wordsTruncated);

        }
    }


    /**
     * Le contenu par défaut du repository qui sera généré
     *
     * @return bool|mixed|string
     */
    public function getRepositoryFileContent(){
        $model_path = "";
        $resource_path = "";

        $stubVariables = [
            'NAMESPACE'            => $this->namespace,
            'MODEL'                => $model_path,
            'RESOURCE'             => $resource_path,
            'CLASS_NAME'           => $this->getSingularClassName($this->getRealFileName()),
        ];


        return $this->getStubContents( $this->getStubPath("repository.stub"), $stubVariables);
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


    /**
     * Remplace les variables du talon (clé) par la valeur defini
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub , $stubVariables = []){
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$'.$search.'$' , $replace, $contents);
        }

        return $contents;
    }

    /**
     * Obtenir le chemin complet de la classe controlleur générée
     *
     * @return string
     */
    public function getRepositoriesFilePath(){
        return base_path($this->getClassPath()) . '.php';
    }

    /**
     * Retourner le nom en majuscules au singulier
     * @param $name
     * @return string
     */
    public function getSingularClassName($name){
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Créer le répertoire pour la classe si nécessaire.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path){
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    protected function getClassPath(){
        return $this->namespace .'\\' .$this->getSingularClassName($this->getRealFileName())."Controller";
    }

}