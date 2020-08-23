<?php

namespace Tests\Domain\Repositories\Eloquent;

use App\Domain\Enuns\TransactionStatusEnum;
use App\Domain\Models\Transaction;
use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Eloquent\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    /** @var TransactionRepository */
    private $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepository = new TransactionRepository();
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf(TransactionRepository::class, $this->transactionRepository);
    }

    /**
     * @test
     */
    public function shouldReturnInProgressTransactionForPayerColumn()
    {
        $payerUser   = factory(User::class)->create();
        $payeeUser   = factory(User::class)->create();
        $payerWallet = factory(Wallet::class)->create(['user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['user_id' => $payeeUser->id]);
        factory(Transaction::class)->create([
            'payer_wallet_id'       => $payerWallet->id,
            'payee_wallet_id'       => $payeeWallet->id,
            'transaction_status_id' => TransactionStatusEnum::IN_PROGRESS
        ]);

        $collection = $this->transactionRepository->findInProgressTransactions($payerWallet->user_id);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(1, $collection);
        $this->assertEquals($payerWallet->user_id, $collection->first()->payer_user_id);
    }

    /**
     * @test
     */
    public function shouldReturnInProgressTransactionForPayeeColumn()
    {
        $payerUser   = factory(User::class)->create();
        $payeeUser   = factory(User::class)->create();
        $payerWallet = factory(Wallet::class)->create(['user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['user_id' => $payeeUser->id]);
        factory(Transaction::class)->create([
            'payer_wallet_id'       => $payerWallet->id,
            'payee_wallet_id'       => $payeeWallet->id,
            'transaction_status_id' => TransactionStatusEnum::IN_PROGRESS
        ]);

        $collection = $this->transactionRepository->findInProgressTransactions($payeeWallet->user_id);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(1, $collection);
        $this->assertEquals($payeeWallet->user_id, $collection->first()->payee_user_id);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyCollectionForUserWithoutInProgressTransaction()
    {
        $payerUser                = factory(User::class)->create();
        $payeeUser                = factory(User::class)->create();
        $withoutTransactionUser   = factory(User::class)->create();
        $payerWallet              = factory(Wallet::class)->create(['user_id' => $payerUser->id]);
        $payeeWallet              = factory(Wallet::class)->create(['user_id' => $payeeUser->id]);
        $withoutTransactionWallet = factory(Wallet::class)->create(['user_id' => $withoutTransactionUser->id]);
        factory(Transaction::class)->create([
            'payer_wallet_id' => $payerWallet->id,
            'payee_wallet_id' => $payeeWallet->id
        ]);

        $collection = $this->transactionRepository->findInProgressTransactions($withoutTransactionWallet->user_id);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(0, $collection);
    }
}
