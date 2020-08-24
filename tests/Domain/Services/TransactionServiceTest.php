<?php

namespace Tests\Domain\Services;

use App\Components\Integrations\PaymentIntegrationService;
use App\Domain\Enuns\TransactionStatusEnum;
use App\Domain\Enuns\UserTypesEnum;
use App\Domain\Exceptions\InvalidUserTypeException;
use App\Domain\Exceptions\MaxWalletAmountExceededException;
use App\Domain\Exceptions\TransactionErrorException;
use App\Domain\Exceptions\WalletWithoutCreditException;
use App\Domain\Models\Transaction;
use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Eloquent\TransactionRepository;
use App\Domain\Repositories\Eloquent\WalletRepository;
use App\Domain\Services\TransactionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\LegacyMockInterface;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    /** @var TransactionService */
    private $transactionService;

    /** @var LegacyMockInterface */
    private $walletRepositoryMock;

    /** @var LegacyMockInterface */
    private $transactionRepositoryMock;

    /** @var LegacyMockInterface */
    private $paymentIntegrationServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletRepositoryMock          = \Mockery::mock(WalletRepository::class);
        $this->transactionRepositoryMock     = \Mockery::mock(TransactionRepository::class);
        $this->paymentIntegrationServiceMock = \Mockery::mock(PaymentIntegrationService::class);
        $this->transactionService            = new TransactionService(
            $this->walletRepositoryMock,
            $this->transactionRepositoryMock,
            $this->paymentIntegrationServiceMock
        );
    }

    /**
     * @test
     */
    public function shouldMakeOneTransaction()
    {
        $payerUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::COMMON]);
        $payeeUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::MERCHANT]);
        $payerWallet = factory(Wallet::class)->create(['amount' => 100.00, 'user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['user_id' => $payeeUser->id]);
        $data        = [
            'value' => 50.00,
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id
        ];
        $fixture     = factory(Transaction::class)->create([
            'payer_wallet_id'       => $payerWallet->id,
            'payee_wallet_id'       => $payeeWallet->id,
            'amount'                => $data['value'],
            'transaction_status_id' => TransactionStatusEnum::FINALIZED
        ]);
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payer'])
            ->andReturn($payerWallet);
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payee'])
            ->andReturn($payeeWallet);
        $this->walletRepositoryMock
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn($payerWallet);
        $this->walletRepositoryMock
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn($payeeWallet);
        $this->paymentIntegrationServiceMock
            ->shouldReceive('authorizeTransaction')
            ->andReturnTrue();
        $this->transactionRepositoryMock
            ->shouldReceive('save')
            ->with([
                'payer_wallet_id'       => $payerWallet->id,
                'payee_wallet_id'       => $payeeWallet->id,
                'amount'                => $data['value'],
                'transaction_status_id' => TransactionStatusEnum::FINALIZED
            ])
            ->andReturn($fixture);

        $transaction = $this->transactionService->makeTransaction($data);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($fixture->id, $transaction->id);
    }

    /**
     * @test
     */
    public function shouldThrowInvalidUserTypeException()
    {
        $payerUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::MERCHANT]);
        $payeeUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::MERCHANT]);
        $payerWallet = factory(Wallet::class)->create(['amount' => 100.00, 'user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['user_id' => $payeeUser->id]);
        $data        = [
            'value' => 50.00,
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id
        ];
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payer'])
            ->andReturn($payerWallet);
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payee'])
            ->andReturn($payeeWallet);

        $this->expectException(InvalidUserTypeException::class);
        $this->expectExceptionMessage('Usuários do tipo Lojista não podem realizar transferências');

        $this->transactionService->makeTransaction($data);
    }

    /**
     * @test
     */
    public function shouldThrowMaxWalletAmountExceededException()
    {
        $payerUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::COMMON]);
        $payeeUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::MERCHANT]);
        $payerWallet = factory(Wallet::class)->create(['amount' => 100.00, 'user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['amount' => 200000.00,'user_id' => $payeeUser->id]);
        $data        = [
            'value' => 50.00,
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id
        ];
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payer'])
            ->andReturn($payerWallet);
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payee'])
            ->andReturn($payeeWallet);


        $this->expectException(MaxWalletAmountExceededException::class);
        $this->expectExceptionMessage('Usuário beneficiado excedeu o limite de créditos');

        $this->transactionService->makeTransaction($data);
    }

    /**
     * @test
     */
    public function shouldThrowWalletWithoutCreditException()
    {
        $payerUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::COMMON]);
        $payeeUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::MERCHANT]);
        $payerWallet = factory(Wallet::class)->create(['amount' => 0.00, 'user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['amount' => 100.00,'user_id' => $payeeUser->id]);
        $data        = [
            'value' => 50.00,
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id
        ];
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payer'])
            ->andReturn($payerWallet);
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payee'])
            ->andReturn($payeeWallet);


        $this->expectException(WalletWithoutCreditException::class);
        $this->expectExceptionMessage('Usuário pagante não tem crédito suficiente para realizar essa transferência');

        $this->transactionService->makeTransaction($data);
    }

    /**
     * @test
     */
    public function shouldThrowTransactionErrorException()
    {
        $payerUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::COMMON]);
        $payeeUser   = factory(User::class)->create(['user_type_id' => UserTypesEnum::MERCHANT]);
        $payerWallet = factory(Wallet::class)->create(['amount' => 100.00, 'user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['amount' => 100.00,'user_id' => $payeeUser->id]);
        $data        = [
            'value' => 50.00,
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id
        ];
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payer'])
            ->andReturn($payerWallet);
        $this->walletRepositoryMock
            ->shouldReceive('findByUserId')
            ->with($data['payee'])
            ->andReturn($payeeWallet);
        $this->walletRepositoryMock
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(TransactionErrorException::class);
        $this->expectExceptionMessage('Não foi possível realizar a transferência, tente novamente mais tarde');

        $this->transactionService->makeTransaction($data);
    }
}
