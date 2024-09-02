<?php


namespace Jsimo\LaravelRepositoryPattern\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface {

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param int|string|Model $id
     * @param array $data
     * @return mixed
     */
    public function update($id,array $data);

    /**
     * @param int|string|Model $id
     * @return mixed
     */
    public function archive($id);

    /**
     * @param int|string|Model $id
     * @return mixed
     */
    public function delete($id);


    /**
     * @param array $data
     * @return mixed
     */
    public function createMany(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateMany(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function archiveMany(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function deleteMany(array $data);

    /**
     * @param null $inputs
     * @return mixed
     */
    public function all($inputs = null) ;

    /**
     * @param int|string|Model $search
     * @param bool $exception
     * @return mixed
     */
    public function find($search,$exception = false) ;

    /**
     * @param int|string|Model $search
     * @param bool $exception
     * @return mixed
     */
    public function show($search,$exception = false) ;


}
