<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

abstract class BaseRepository implements BaseRepositoryInterface {

    use ProcessableTransaction,HasModelQuery;

    protected $model = null;
    protected $resource = null;
    protected $validator = null;

    /**
     * BaseRepository constructor.
     * @param string $model
     * @param null|string $resource
     * @param null|string $validator
     * @throws Exception
     */
    public function __construct($model,$resource = null, $validator = null) {

        if (!class_exists($model)){
            throw new Exception("Eloquent Model class $model doesn't exist.");
        }
        if ($resource and !class_exists($resource)){
            throw new Exception("Http Resource class $resource doesn't exist.");
        }
        if ($model and class_exists($model)){
            $this->model = (new $model);

            if ( !($this->model instanceof Model)){
                throw new Exception("Eloquent Model object $model must instance of Eloquent\Model");
            }

            if ($resource and class_exists($resource)){
                $this->resource = new $resource($this->model);
            }

            $this->validator = (new $validator);
        }

    }


    /**
     * @param int|string|array|Model|Collection $search
     * @param bool $exception
     * @return mixed
     * @throws Exception
     */
    public function find($search, $exception = false){
        $model  = null;

        if($search instanceof Model) $model = $search;

        if (is_string($search) or is_int($search)) $model = $this->model->find($search);

        $with = $search["with"] ?? [];
        $query = $search["query"] ?? null;

        if (is_array($search) and $query and is_callable($query) ){
            $model = $this->query()->with($with)
                                 ->where(fn($q) => $query($q))
                                 ->first();
        }

        if (!$model and $exception){
            throw new \Exception("Model or resource not found.");
        }
        return $model;
    }


    /**
     * @param null $inputs
     * @return mixed
     * @throws Exception
     */
    public function all($inputs = null){
        $model =  $this->model;

        $query = $inputs['query'] ?? null;
        $paginate = $inputs['paginate'] ?? false;
       // $page = $inputs['page'] ?? 1;
        $per_page = $inputs['per_page'] ?? 10;
        $columns = $inputs['columns'] ?? ['*'];


        if ( !is_null($query) and is_callable($query)){
            $model =  $this->query()->where(fn ($q) => $query($q))
                            // ->when($paginate,fn ($q) => $q->paginate())
            ;
        }



        $models = ($paginate) ? $model->paginate($per_page,$columns) : $model->get();
        return ($this->resource) ? $this->resource->collection($models) : $models;
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws ValidationException
     */
    public function create(array $data){
        $payload = $this->validate($this->validator,$data);
//        $payload = $this->model->validate($data,config("repository-pattern.exception_when_validate",true));
        return  $this->process(
                    [
                        "action" => RepositoryActionType::CREATE,
                        "data" => $data,
                        "payload" => $payload,
                    ],
                    fn (array $payload)=> $this->handleBeforeUpdating($payload),
                    fn (Model $model,array $data)=> $this->handleAfterUpdating($model,$data)
                );
    }

    /**
     * @param int|string|Model $search
     * @param array $data
     * @return array|mixed
     * @throws ValidationException
     * @throws Exception
     */
    public function update($search,array $data){
      //  $model = $this->find($search);
       // $payload = $model->validate($data,config("repository-pattern.exception_when_validate",true));

        $payload = $this->validate($this->validator,$data);
        return  $this->process(
                    [
                        "action" => RepositoryActionType::UPDATE,
                        "data" => $data,
                        "payload" => $payload,
                        "search" => $search,
                    ],
                    fn (array $payload) => $this->handleBeforeUpdating($payload),
                    fn (Model $model,array $data) => $this->handleAfterUpdating($model,$data)
                );
    }


    /**
     * @param Model|int|string $search
     * @return bool|mixed|null
     * @throws Exception
     */
    public function archive($search){
        $model = $this->find($search);
        return $model->delete();
    }

    /**
     * @param Model|int|string $search
     * @return bool|mixed|null
     * @throws Exception
     */
    public function delete($search){
        $model = $this->find($search);
        return $model->forceDelete();
    }

    /**
     * @param $search
     * @param bool $exception
     * @return array|Collection|Model|int|mixed|string|null
     * @throws Exception
     */
    public function show($search, $exception = false){
        $model = $this->find($search,$exception);
        return ($this->resource) ? $this->resource->make($model) : $model;
    }


}
