<?php


namespace Jsimo\LaravelRepositoryPattern\Controllers;


use Jsimo\LaravelRepositoryPattern\Pattern\BaseRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateBaseController;

abstract class BaseController extends IlluminateBaseController {

    use AuthorizesRequests, ValidatesRequests;

    protected $repository;

    public function __construct(BaseRepository $repository){
        $this->repository = $repository;
    }


    public function create(Request $request){
        $data = $request->all();
        $response = $this->repository->create($data);
        return $this->getHttpResult($response);
    }


    public function update(Request $request , $id){
        $response = $this->repository->update($id);
        return $this->getHttpResult($response);
    }

    public function delete(Request $request , $id){
        $response = $this->repository->archive($id);
        return $this->getHttpResult($response);
    }

    public function createMany(Request $request){
        $data = $request->all();
        $response = $this->repository->create($data);
        return $this->getHttpResult($response);
    }

    public function updateMany(Request $request){
        $data = $request->all();
        $response = $this->repository->updateMany($data);
        return $this->getHttpResult($response);
    }

    public function deleteMany(Request $request){
        $data = $request->all();
        $response = $this->repository->archiveMany($data);
        return $this->getHttpResult($response);
    }


    public function show(Request $request , $id){
        return $this->repository->find($id);
    }

    public function all(Request $request){
        return $this->repository->all($request->all());
    }

    protected function getHttpResult($response){
        $success = $response['success'] ?? true;
        return response()->json($response,($success) ? 200 : 500);
    }

}
