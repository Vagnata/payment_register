<?php

namespace App\Http\Requests\User;

use App\Domain\Enuns\UserTypesEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JansenFelipe\Utils\Utils;
use Symfony\Component\HttpFoundation\Response;

class PutRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount'  => ['required', 'float', 'max:200000']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response['message'] = current($validator->errors()->all());

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
