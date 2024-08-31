<?php


namespace Jsimo237\LaravelRepositoryPattern\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait ProcessableTransaction {


    /**
     * @param FormRequest $formRequest
     * @param array $data
     * @param bool $exception
     * @return array
     */
    public function validate(FormRequest $formRequest,array $data, $exception = true){
        $rules = (method_exists($formRequest, 'rules') ? $formRequest->rules() : []);
        $messages = (method_exists($formRequest, 'messages') ? $formRequest->messages() : []);
        $attributes = (method_exists($formRequest, 'attributes') ? $formRequest->attributes() : []);

        $validator = validator($data,$rules,$messages,$attributes);

        $failed = $validator->fails();

        if ($failed){
            throw_if($exception, new ValidationException($validator) );
            return  $validator->messages()->errors();
        }

        // validation pass
        return  $validator->validated();
    }


    public function handleAfterUpdating($model, $data){
        return [$model , $data];
    }

    /**
     * @param $payload
     * @return array
     */
    public function handleBeforeUpdating($payload){
        return $payload;
    }


    /**
     * @param array $options
     * @param null|Closure $before
     * @param null|Closure $after
     * @return array
     */
    public function execute(array $options , $before = null , $after  = null ){

        DB::beginTransaction();
        $response = [ "success" => false, "message" => "Failed", ];

        try {

            $action = $options['action'];
            $data = $options['data'];
            $payload = $options['payload'];
            $id = $options['id'] ?? null;

            $primary_key_name = $this->model->getKeyName();
            $model = null;

            if ($id) {
                if ($id instanceof Model) {
                    $model = $id;
                }
                elseif (!is_array($id) or is_int($id) or is_string($id)){
                    $model = $this->model->findOrFail($id);
                }
                elseif (is_array($id)){
                    $first = $id[0];
                    if($first instanceof Model) $id = $id->pluck($primary_key_name)->toArray();
                    $model = $this->model->whereIn($primary_key_name,$id)->get();
                }
                else{
                    $model = $id;
                }
            }

            if ($before) $payload = $before($payload);


            switch ($action){
                case RepositoryActionType::CREATE :
                    $model = get_class($this->model)::create($payload); //Cree le model en BD
                    $response["message"] = action_message('create');
                    break;

                case RepositoryActionType::UPDATE :
                    if($payload) {
                        // Modifie le(s) model(s)
                        is_array($id) ? $model->map->update($payload) : $model->update($payload);
                    }

                    $response["message"] = action_message('update');
                    break;

                case RepositoryActionType::DELETE :

                    is_array($id) ? $model->map->delete() : $model->delete();  // Supprime le(s) model(s)

                    $response["message"] = action_message('delete');
                    break;
            }

            if (in_array($action,[RepositoryActionType::CREATE,RepositoryActionType::UPDATE, RepositoryActionType::DELETE])){

                if ($after) [$model,$data] = $after($model,$data);

                if ($action === RepositoryActionType::DELETE){
                    $response["resource"] = null;
                }

                if (in_array($action,[RepositoryActionType::CREATE,RepositoryActionType::UPDATE])){
                    $response["success"] = filled($model); //000543591

                    $response["resource"] = is_array($id)
                                            ? $this->all(["query" => fn($q) => $q->where($primary_key_name,$id)])
                                            : $this->find($model->getKey());
                }
            }

            DB::commit();

        }catch (\Exception $exception){
            DB::rollBack();
            write_log(get_class($this)."/$action",$exception,"error");
            $response['message'] = general_error($exception);
             $response['exception'] = $exception->getTraceAsString();
        }

        return $response;
    }


}
