<?php


namespace Jsimo\LaravelRepositoryPattern\Examples;


use Illuminate\Database\Eloquent\Model;
use Jsimo\LaravelRepositoryPattern\Pattern\BaseRepositoryInterface;
use Jsimo\LaravelRepositoryPattern\Traits\OverloadRepository;

class BasiRepositoryExample implements BaseRepositoryInterface {

    use OverloadRepository;

}