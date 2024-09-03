<?php


namespace Jsimo\LaravelRepositoryPattern\Commands;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait  HasStub {

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
            $contents = str_replace('{{ '.$search.' }}' , $replace, $contents);
        }

        return $contents;
    }


    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub){
//        dd( __DIR__ . "../../stubs/$stub");
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                ? $customPath
                : $stub;

       // return __DIR__ . "../../stubs/repository-pattern/$stub";
    }


    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     * @param null $suffix
     * @param null $rootNamespace
     * @return string
     */
    protected function qualifyClass($name, $suffix = null,$rootNamespace = null){
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $rootNamespace ?? $this->rootNamespace();

        $class_name = Str::studly(class_basename($name));

        if ($suffix and !str_contains($class_name,$suffix)){
            $name .= $suffix;
        }

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    protected function getClassPathWithSuffixe($path,$suffix = null){
        $path = ltrim($path, '\\/');
        $path = str_replace('/', '\\', $path);
        $index = strpos($path,"\\");

       // $class_name = Str::studly(class_basename($name));

        if ($suffix and !str_contains($class_name,$suffix)){
            $name .= $suffix;
            $class_name .= $suffix;
        }

       if ($index !== false){
           $className = Str::substr($path,$index,-1);
       }

       return [$className , $path];
    }

}