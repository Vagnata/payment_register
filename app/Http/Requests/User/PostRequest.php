<?php

namespace App\Http\Requests\User;

use App\Domain\Enuns\UserTypesEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JansenFelipe\Utils\Utils;
use Symfony\Component\HttpFoundation\Response;

class PostRequest extends FormRequest
{
    public function all($keys = null)
    {
        $input         = parent::all();
        $input['cpf']  = !is_null($input['cpf']) ? Utils::unmask($input['cpf']) : null;
        $input['cnpj'] = !is_null($input['cnpj']) ? Utils::unmask($input['cnpj']) : null;

        return $input;
    }

    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:100', 'unique:users'],
            'cpf'          => ['required_without:cnpj', 'cpf', 'nullable', 'unique:users'],
            'cnpj'         => ['required_without:cpf', 'cnpj', 'nullable', 'unique:users'],
            'password'     => ['sometimes', 'string', 'max:100'],
            'user_type_id' => ['required', Rule::in(UserTypesEnum::toArray())]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response['message'] = current($validator->errors()->all());

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
