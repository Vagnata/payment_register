<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class PostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'value' => ['required', 'numeric', 'max:200000.00', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'payer' => ['required', 'integer', 'exists:users,id'],
            'payee' => ['required', 'integer', 'exists:users,id']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response['message'] = current($validator->errors()->all());

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
