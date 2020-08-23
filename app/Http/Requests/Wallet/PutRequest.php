<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class PutRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount'  => ['required', 'numeric', 'max:200000.00', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response['message'] = current($validator->errors()->all());

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
