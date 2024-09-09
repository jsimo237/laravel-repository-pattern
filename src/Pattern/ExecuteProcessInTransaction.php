<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;

use Closure;
use Illuminate\Support\Facades\DB;

trait ExecuteProcessInTransaction {


    /**
     * @param Closure $callback
     * @param null|Closure $callback_exception
     * @return array
     */
    public function execute($callback, $callback_exception = null){
        DB::beginTransaction();
        $response = [ "success" => true, "message" => "Failed", ];
        try {
           $response = $callback($response);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            write_log(get_class($this)."/".__FUNCTION__,$exception,"error");
            $response['success'] = false;
            $response['message'] = general_error($exception);
            $response['exception'] = $exception->getTraceAsString();
            if ($callback_exception) $callback_exception();
        }

        return $response;
    }

}