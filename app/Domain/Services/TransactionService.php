<?php

namespace App\Domain\Services;

use App\Component\Integrations\PaymentIntegrationService;
use App\Domain\Enuns\TransactionStatusEnum;
use App\Domain\Enuns\UserTypesEnum;
use App\Domain\Exceptions\InvalidUserTypeException;
use App\Domain\Exceptions\MaxWalletAmountExceededException;
use App\Domain\Exceptions\TransactionErrorException;
use App\Domain\Exceptions\WalletWithoutCreditException;
use App\Domain\Models\Transaction;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Contracts\TransactionRepositoryInterface;
use App\Domain\Repositories\Contracts\WalletRepositoryInterface;
use App\Domain\Repositories\Eloquent\TransactionRepository;
use App\Domain\Repositories\Eloquent\WalletRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

class TransactionService
{
    /** @var WalletRepository */
    private $walletRepository;

    /** @var TransactionRepository */
    private $transactionRepository;

    /** @var PaymentIntegrationService */
    private $paymentIntegrationService;

    public function __construct(
        WalletRepositoryInterface $walletRepository,
        TransactionRepositoryInterface $transactionRepository,
        PaymentIntegrationService $paymentIntegrationService
    ) {
        $this->walletRepository          = $walletRepository;
        $this->transactionRepository     = $transactionRepository;
        $this->paymentIntegrationService = $paymentIntegrationService;
    }

    /**
     * @param array $data
     * @return Transaction
     * @throws InvalidUserTypeException
     * @throws MaxWalletAmountExceededException
     * @throws WalletWithoutCreditException
     * @throws TransactionErrorException
     */
    public function makeTransaction(array $data): Transaction
    {
        $data = new Fluent($data);

        $payerWallet = $this->walletRepository->findByUserId($data->get('payer'));
        $payeeWallet = $this->walletRepository->findByUserId($data->get('payee'));

        $this->validatePayerType($payerWallet->user->user_type_id);
        $this->validatePayerCredits($payerWallet->amount, $data->get('value'));
        $this->validatePayeeTotalCredits($payeeWallet->amount, $data->get('value'));

        return $this->insertTransaction($payerWallet, $payeeWallet, $data->get('value'));
    }

    private function validatePayerCredits(float $amount, float $transferValue): void
    {
        if ($amount < $transferValue) {
            throw new WalletWithoutCreditException();
        }
    }

    private function validatePayeeTotalCredits(float $amount, float $transferValue): void
    {
        if (($amount + $transferValue) > Wallet::MAX_AMOUNT) {
            throw new MaxWalletAmountExceededException(trans('exceptions.wallet.transfer.exceeded_credits'));
        }
    }

    private function validatePayerType(int $userTypeId): void
    {
        if ($userTypeId == UserTypesEnum::MERCHANT) {
            throw new InvalidUserTypeException();
        }
    }

    private function insertTransaction(Wallet $payerWallet, Wallet $payeeWallet, float $transferValue): Transaction
    {
        try {
            DB::beginTransaction();

            $this->walletRepository->update($payerWallet, ['amount' => $payerWallet->amount - $transferValue]);
            $this->walletRepository->update($payeeWallet, ['amount' => $payeeWallet->amount + $transferValue]);

            $this->paymentIntegrationService->authorizeTransaction();

            $transaction = $this->transactionRepository->save([
                'payer_wallet_id'       => $payerWallet->id,
                'payee_wallet_id'       => $payeeWallet->id,
                'amount'                => $transferValue,
                'transaction_status_id' => TransactionStatusEnum::FINALIZED
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            Log::error('TRANSACTION ERROR: ' . $exception->getMessage());
            DB::rollBack();
            throw new TransactionErrorException();
        }

        return $transaction;
    }
}
