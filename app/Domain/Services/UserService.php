<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\FailUserInsertionException;
use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Contracts\UserRepositoryInterface;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Support\Fluent;
use JansenFelipe\Utils\Utils;

class UserService
{
    private $userRepository;
    private $walletRepository;

    public function __construct(UserRepositoryInterface $userRepository, WalletRepositoryInterface $walletRepository)
    {
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
    }

    public function addUser(array $data): User
    {
        try {
            $user = $this->insertUser($data);
            $this->initEmptyWallet($user);

            return $user;
        } catch (\Exception $exception) {
            throw new FailUserInsertionException();
        }
    }

    private function insertUser(array $data): User
    {
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
    }

    private function initEmptyWallet(User $user): Wallet
    {
        return $this->walletRepository->save(['user_id' => $user->id, 'amount'  => 0.00]);
    }
}
