<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface {

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param int|string|Model $search
     * @param array $data
     * @return mixed
     */
    public function update($search,array $data);

    /**
     * @param int|string|Model $search
     * @param bool $exception
     * @return mixed
     */
    public function find($search,$exception = false) ;

    /**
     * @param int|string|Model $search
     * @return mixed
     */
    public function archive($search);

    /**
     * @param int|string|Model $search
     * @return mixed
     */
    public function delete($search);


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

}
