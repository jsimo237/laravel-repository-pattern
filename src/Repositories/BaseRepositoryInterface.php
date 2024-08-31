<?php


namespace Jsimo237\LaravelRepositoryPattern\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface BaseRepositoryInterface {

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param mixed $id
     * @param array $data
     * @return mixed
     */
    public function update($id,array $data);

    /**
     * @param int|string|array $id
     * @return mixed
     */
    public function archive($id);

    /**
     * @param int|string|array|Collection $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param null|string|array|callable $$inputs
     * @return mixed
     */
    public function all($inputs) ;


    /**
     * @param int|string|array|Collection $id
     * @return mixed
     */
    public function find($id) ;

//    /**
//     * @param Request $request
//     * @param array $data
//     * @return mixed
//     */
   // public function validate($request, $data) ;


}
