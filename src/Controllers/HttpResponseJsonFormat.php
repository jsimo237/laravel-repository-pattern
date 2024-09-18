<?php


namespace Jsimo\LaravelRepositoryPattern\Controllers;


use Illuminate\Http\JsonResponse;

trait HttpResponseJsonFormat {

    /**
     * @param $response
     * @return JsonResponse
     */
    protected function getHttpResult($response){
        $success = $response['success'] ?? true;
        return response()->json($response,($success) ? 200 : 500);
    }

}