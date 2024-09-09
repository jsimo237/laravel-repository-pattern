<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;

use Illuminate\Database\Eloquent\Model;
use \Closure;

trait ProcessableTransaction {

    use ExecuteProcessInTransaction,Validable;


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
    public function process(array $options = [] , $before = null , $after  = null ){

        return $this->execute(function($response) use ($options,$before,$after) {
            $action = $options['action'];
            $data = $options['data'];
            $payload = $options['payload'];
            $search = $options['search'] ?? null;

            $primary_key_name = $this->model->getKeyName();
            $model = null;

            if ($search) {
                if ($search instanceof Model) {
                    $model = $search;
                }
                elseif (!is_array($search) or is_int($search) or is_string($search)){
                    $model = $this->model->findOrFail($search);
                }
                elseif (is_array($search)){
                    $first = $search[0];
                    if($first instanceof Model) $search = $search->pluck($primary_key_name)->toArray();
                    $model = $this->model->whereIn($primary_key_name,$search)->get();
                }
                else{
                    $model = $search;
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
                        is_array($search)
                            ? $model->map->update($payload)
                            : $model->update($payload);
                    }

                    $response["message"] = action_message('update');
                    break;

                case RepositoryActionType::DELETE :

                    is_array($search) ? $model->map->delete() : $model->delete();  // Supprime le(s) model(s)

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

                    $response["resource"] = is_array($search)
                                            ? $this->all(["query" => fn($q) => $q->where($primary_key_name,$search)])
                                            : $this->find($model->getKey());
                }
            }

            return $response;
        });

    }


}
