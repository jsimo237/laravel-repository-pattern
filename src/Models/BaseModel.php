<?php


namespace Jsimo\LaravelRepositoryPattern\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


abstract class BaseModel extends Model {

    use HasFactory, Validable;

    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool $timestamps
     */
    public $timestamps = true;


    /**
     * Indicates whether the model uses soft deletes.
     *
     * @var bool $softDelete
     */
    protected $softDelete = true;


    /**
     * Gets the validation error messages.
     * @param bool $returnArray
     *
     * @return mixed
     */
    public function errors($returnArray = true){
        return $returnArray ? $this->errors->toArray() : $this->errors;
    }



}
