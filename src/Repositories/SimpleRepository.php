<?php


namespace Jsimo237\LaravelRepositoryPattern\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

abstract class SimpleRepository extends BaseRepository {

    public $model = null;
    public $resource = null;
    public $validator = null;

    /**
     * SimpleRepository constructor.
     * @param string $model
     * @param null|string $resource
     * @param null|string $validator
     */
    public function __construct($model = null,$resource = null, $validator = null) {
        parent::__construct($model,$resource,$validator);

    }


}
