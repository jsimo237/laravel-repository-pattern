<?php


if(!function_exists("action_message")){

    /**
     * @param string $action
     * @param bool $success
     * @return mixed
     */
    function action_message($action = "",$success = true){
        $title = __("Opération");
        if ($action == "create"){
            $title = __("Recording");
        }
        if ($action == "update"){
            $title = __("Updating");
        }elseif ($action == "delete"){
            $title = __("Deleting");
        }
        elseif ($action == "activate"){
            $title = (booleanVal(request()->get("is_active") ?? false))
                ? __("Enabling")
                : __("Disabling");
        }
        return ($success) ? __("Successful :title.",['title' => $title]) : __("Failed :title",['title' => $title]);
    }
}

if(!function_exists("booleanVal")){

    /**Retourne la valeur booléene
     * @param int|string $value
     * @return boolean
     */
    function booleanVal($value){
        return filter_var($value ?? true,FILTER_VALIDATE_BOOLEAN);
    }
}

if(!function_exists("general_error")){

    /**
     * @param null $exception
     * @return mixed
     */
    function general_error($exception = null){
        $message = __("Une erreur imprévue est survenue lors de l'opération.");
        $exceptions = [
            1
        ];
        if ( filled($exception) and in_array(get_class($exception),$exceptions) ){
            $message = $exception->getMessage();
        }
        elseif ( (filled($exception) and app()->isLocal())){
            $message = $exception->getMessage();
        }
        return $message;
    }
}

if (! function_exists('format_exception_message')) {

    /**
     * @param mixed $exception
     * @return string
     */
    function format_exception_message($exception){
        return $exception->getMessage()." file = ".$exception->getFile()."| line = ".$exception->getLine()."| trace = ".$exception->getTraceAsString();
    }
}

if (!function_exists('write_log')) {

    function write_log($directory,$message,$type = 'info',$name = "action"){

        if ($message instanceof Exception){
            $message = format_exception_message($message);
        }

        if (is_array($message) or is_object($message)){
            $message = json_encode($message);
        }

        if (blank($directory) or blank($name)){
            if (!empty($message)) {
                Log::error($message);
            }
        }else{
            $logger = new Logger(Str::Slug($name));
            /*fichier journal qui sera crée de journal d'erreur*/
            $log_file = storage_path()."/logs/$directory/log.log";

            /*Génération de journal d'erreur*/
            $logger = $logger->pushHandler(new RotatingFileHandler($log_file));

            if ($type != "info"){
                $logger->error($message);
            }else{
                $logger->info($message);
            }

            if ($directory){
                $files= File::allFiles(storage_path("logs/$directory"));
                $deletable = env("LOG_FILE_DELETED_AFTER","-10days");
                foreach ($files as $file) {
                    $path = $file->getRealPath();
                    $lastModified = File::lastModified($path);
                    $lastModified = date('Y-m-d',$lastModified);
                    $deletable_date = date("Y-m-d",strtotime($deletable));
                    // echo ("path= $path; lastModified = $lastModified ; deletableDate = $deletable_date ; deletable = $deletable \n");

                    /*si le fichier a déja fait plus de*/
                    if ($deletable_date > $lastModified){
                        File::delete($path);
                    }
                }
            }
        }

        return true;
    }
}

