<?php


namespace Jsimo237\LaravelRepositoryPattern\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

abstract class BaseRepository implements BaseRepositoryInterface {

    use ProcessableTransaction;

    public $model = null;
    public $resource = null;
    public $validator = null;

    protected $item;

    /**
     * BaseRepository constructor.
     * @param string $model
     * @param null|string $resource
     * @param null|string $validator
     */
    public function __construct($model = null,$resource = null, $validator = null) {
        if ($model and class_exists($model)){
            $this->model = (new $model);

            if ($resource and class_exists($resource)){
                $this->resource = new $resource($this->model);
            }

            if ($validator){
                $this->validator = $validator;
            }
        }

    }



    /**
     * @param array|Model|Collection|int|string $id
     * @return mixed
     */
    public function find($id){
        $model = $this->model->findOrFail($id);
        return $this->resource->make($model);
    }


    /**
     * @param null $inputs
     * @return mixed
     */
    public function all($inputs = null){
        $model =  $this->model;

        $query = $inputs['query'] ?? null;
        $paginate = $inputs['paginate'] ?? false;

        // (new Model)->newModelQuery()

        // $builder = $data['builder'] ?? false;
        // if ($builder) $model->newQuery();
        if ( !is_null($query) and is_callable($query)){
            $model =  $model->newQuery()
                            ->where(fn ($q) => $query($q))
                            // ->when($paginate,fn ($q) => $q->paginate())
            ;
        }

        $models = ($paginate) ? $model->paginate() : $model->get();
        return $this->resource->collection($models);
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws ValidationException
     */
    public function create(array $data){
        $validator = (new $this->validator);
        $payload = $this->validate($validator,$data);
        return  $this->execute(
                    [
                        "action" => RepositoryActionType::CREATE,
                        "data" => $data,
                        "payload" => $payload,
                    ],
                    fn ($payload)=> $this->handleBeforeUpdating($payload),
                    fn ($model,$data)=> $this->handleAfterUpdating($model,$data)
                );
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return array|mixed
     * @throws ValidationException
     */
    public function update($id,array $data){
        $validator = new $this->validator(['id' => $id]);
        $payload = $this->validate($validator,$data);
        return  $this->execute(
                    [
                        "action" => RepositoryActionType::UPDATE,
                        "data" => $data,
                        "payload" => $payload,
                        "id" => $id,
                    ],
                    fn ($payload) => $this->handleBeforeUpdating($payload),
                    fn ($model,$data) => $this->handleAfterUpdating($model,$data)
                );
    }



    public function archive($data){
        return $this->model->delete($data);
    }

    public function delete($data){
        return $this->model->forceDelete($data);
    }


}
