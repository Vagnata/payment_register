<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\FailUserInsertionException;
use App\Domain\Models\User;
use App\Domain\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Fluent;
use JansenFelipe\Utils\Utils;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addUser(array $data): User
    {
        try {
            $data = new Fluent($data);

            $userData = [
                'name'         => $data->get('name'),
                'email'        => $data->get('email'),
                'cpf'          => !is_null($data->get('cpf')) ? Utils::unmask($data->get('cpf')) : null,
                'cnpj'         => !is_null($data->get('cnpj')) ? Utils::unmask($data->get('cnpj')) : null,
                'password'     => sha1($data->get('password')),
                'user_type_id' => $data->get('user_type_id'),
            ];

            return $this->userRepository->save($userData);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            throw new FailUserInsertionException();
        }
    }
}
