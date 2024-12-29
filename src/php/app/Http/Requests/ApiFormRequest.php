<?php
/**
 * Class ApiFormRequest
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        $response = [
            "status" => "FAIL",
            "error" => [
                "code" => 0,
                "message" => "Validation Error",
                "message_detail" => $validator->errors()
            ]
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
