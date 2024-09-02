<?php


namespace Jsimo\LaravelRepositoryPattern\Models;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use \Throwable;

trait Validable {

    /**
     * The validation errors of model.
     *
     * @var mixed $errors
     */
    protected $errors;

    public function validationRules(){
        return [];
    }

    public function validationMessages(){
        return [];
    }

    public function validationAttributes(){
        return [];
    }

    protected function validationSchema(){
        return [
            $this->validationRules(),
            $this->validationMessages(),
            $this->validationAttributes()
        ];
    }


    /**
     * Validate the model instance.
     *
     * @param array $data
     * @param bool $exception
     * @return array
     * @throws Throwable
     */
    public function validate(array $data,$exception = false){

        [$rules,$messages,$attributes] = $this->validationSchema();

        $validator = Validator::make($data, $rules, $messages, $attributes);

        $failed = $validator->fails();

        if ($failed) {
            $this->errors = $validator->messages();

            if ($exception) throw new ValidationException($validator);

            return  $validator->errors()->messages();
        }
        return  $validator->validated();
    }

}