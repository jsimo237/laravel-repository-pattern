<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;


use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasModelQuery {

    /**
     * @return Builder
     * @throws Exception
     */
    public function query() : Builder{
        if ($this->model) return $this->model->newQuery();

        throw new Exception('Cannot get ModelQuery Instance');
    }

}