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
                'cpf'          => Utils::unmask($data->get('cpf')),
                'cnpj'         => Utils::unmask($data->get('cnpj')),
                'password'     => sha1($data->get('password')),
                'user_type_id' => $data->get('user_type_id'),
            ];

            return $this->userRepository->save($userData);
        } catch (\Exception $exception) {
            throw new FailUserInsertionException();
        }
    }
}
