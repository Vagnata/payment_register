<?php

namespace Tests\Domain\Services;

use App\Domain\Exceptions\MaxWalletAmountExceededException;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Eloquent\WalletRepository;
use App\Domain\Services\WalletService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    /** @var WalletService */
    private $walletService;

    /** @var LegacyMockInterface */
    private $walletRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletRepositoryMock = \Mockery::mock(WalletRepository::class);
        $this->walletService        = new WalletService($this->walletRepositoryMock);
    }

    /**
     * @test
     */
    public function shouldIncreaseCurrentWalletAmount()
    {
        $fixture         = factory(Wallet::class)->create();
        $initialAmount   = $fixture->amount;
        $data            = [
            'user_id' => $fixture->user_id,
            'amount'  => $this->faker->randomFloat(2, 3, 4)
        ];
        $finalAmount     = $initialAmount + $data['amount'];
        $fixture->amount = $finalAmount;
        $this->walletRepositoryMock->shouldReceive('findByUserId')
            ->with($fixture->user_id)
            ->andReturn($fixture);
        $this->walletRepositoryMock->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn($fixture);

        $wallet = $this->walletService->addCreditToWallet($data);

        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertEquals($wallet->amount, $finalAmount);
    }

    /**
     * @test
     */
    public function shouldThrowMaxWalletAmountExceededException()
    {
        $fixture         = factory(Wallet::class)->create(['amount' => 50000.00]);
        $data            = [
            'user_id' => $fixture->user_id,
            'amount'  => 200000.00
        ];
        $this->walletRepositoryMock->shouldReceive('findByUserId')
            ->with($fixture->user_id)
            ->andReturn($fixture);
        $this->walletRepositoryMock->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn($fixture);

        $this->expectException(MaxWalletAmountExceededException::class);
        $this->expectExceptionMessage('Limite máximo da carteira foi excedido. Operação não realizada.');

        $this->walletService->addCreditToWallet($data);
    }
}
