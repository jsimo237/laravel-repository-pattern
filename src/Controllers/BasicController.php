<?php


namespace Jsimo\LaravelRepositoryPattern\Controllers;

use Jsimo\LaravelRepositoryPattern\Pattern\BasicRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as IlluminateBaseController;

abstract class BasicController extends IlluminateBaseController {

    use AuthorizesRequests, ValidatesRequests;

    protected $repository;

    public function __construct(BasicRepository $repository){
        $this->repository = $repository;
    }


}
