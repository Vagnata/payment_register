<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\FailCreditUpdateException;
use App\Domain\Exceptions\MaxWalletAmountExceededException;
use App\Domain\Exceptions\WalletNotFoundException;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Support\Fluent;

class WalletService
{
    private $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function addCreditToWallet(array $data): Wallet
    {
        $data = new Fluent($data);

        $wallet    = $this->findWalletByUserId((int) $data->get('user_id'));
        $newAmount = $this->validateMaxWalletAmount($wallet->amount, $data->get('amount'));

        try {
            return $this->walletRepository->update($wallet, ['amount' => $newAmount]);
        } catch (\Exception $exception) {
            throw new FailCreditUpdateException();
        }
    }

    private function validateMaxWalletAmount(float $currentAmount, float $depositAmount): float
    {
        $sum = $currentAmount + $depositAmount;

        if ($sum > Wallet::MAX_AMOUNT) {
            throw new MaxWalletAmountExceededException();
        }

        return $sum;
    }

    private function findWalletByUserId(int $userId): Wallet
    {
        $wallet = $this->walletRepository->findByUserId($userId);

        if (!$wallet instanceof Wallet) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }
}
