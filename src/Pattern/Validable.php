<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait Validable {

    /**
     * @param FormRequest $formRequest
     * @param array $data
     * @param bool $exception
     * @return array
     * @throws ValidationException
     */
    public function validate(FormRequest $formRequest,array $data, $exception = true){
        $rules = (method_exists($formRequest, 'rules') ? $formRequest->rules() : []);
        $messages = (method_exists($formRequest, 'messages') ? $formRequest->messages() : []);
        $attributes = (method_exists($formRequest, 'attributes') ? $formRequest->attributes() : []);

        $validator = Validator::make($data,$rules,$messages,$attributes);

        $failed = $validator->fails();

        if ($failed){
            if ($exception) throw new ValidationException($validator);
            return  $validator->errors()->messages();
        }

        // validation pass
        return  $validator->validated();
    }

}