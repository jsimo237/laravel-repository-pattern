<?php


namespace Jsimo\LaravelRepositoryPattern\Controllers;


use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Jsimo\LaravelRepositoryPattern\Pattern\BaseRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateBaseController;

abstract class BaseController extends IlluminateBaseController {

    use AuthorizesRequests, ValidatesRequests,HttpResponseJsonFormat;

    protected $repository;

    public function __construct(BaseRepository $repository){
        $this->repository = $repository;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request){
        $data = $request->all();
        $response = $this->repository->create($data);
        return $this->getHttpResult($response);
    }


    /**
     * @param Request $request
     * @param mixed $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request , $id){
        $data = $request->all();
        $response = $this->repository->update($id,$data);
        return $this->getHttpResult($response);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Request $request , $id){
        $response = $this->repository->archive($id);
        return $this->getHttpResult($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createMany(Request $request){
        $data = $request->all();
        $response = $this->repository->create($data);
        return $this->getHttpResult($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateMany(Request $request){
        $data = $request->all();
        $response = $this->repository->updateMany($data);
        return $this->getHttpResult($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMany(Request $request){
        $data = $request->all();
        $response = $this->repository->archiveMany($data);
        return $this->getHttpResult($response);
    }


    /**
     * @param Request $request
     * @param $id
     * @return array|Collection|Model|int|mixed|string|null
     * @throws Exception
     */
    public function show(Request $request , $id){
        return $this->repository->find($id);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function all(Request $request){
        return $this->repository->all($request->all());
    }

}
