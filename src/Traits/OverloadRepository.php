<?php


namespace Jsimo\LaravelRepositoryPattern\Traits;


use Illuminate\Database\Eloquent\Model;

trait OverloadRepository {

    /**
     * @param array $data
     * @return mixed|void
     */
    public function create(array $data){
        // TODO: Implement create() method.
    }


    /**
     * @param Model|int|string $search
     * @param array $data
     * @return mixed|void
     */
    public function update($search, array $data){
        // TODO: Implement update() method.
    }


    /**
     * @param Model|int|string $search
     * @return mixed|void
     */
    public function archive($search){
        // TODO: Implement archive() method.
    }

    /**
     * @param Model|int|string $search
     * @return mixed|void
     */
    public function delete($search){
        // TODO: Implement delete() method.
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function createMany(array $data){
        // TODO: Implement createMany() method.
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function updateMany(array $data){
        // TODO: Implement updateMany() method.
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function archiveMany(array $data){
        // TODO: Implement archiveMany() method.
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function deleteMany(array $data){
        // TODO: Implement deleteMany() method.
    }

    /**
     * @param null $inputs
     * @return mixed|void
     */
    public function all($inputs = null){
        // TODO: Implement all() method.
    }


    /**
     * @param Model|int|string $search
     * @param bool $exception
     * @return mixed|void
     */
    public function find($search, $exception = false){
        // TODO: Implement find() method.
    }


    /**
     * @param Model|int|string $search
     * @param bool $exception
     * @return mixed|void
     */
    public function show($search, $exception = false){
        // TODO: Implement show() method.
    }

}