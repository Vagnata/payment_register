<?php

namespace Tests\Domain\Repositories\Eloquent;

use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Eloquent\WalletRepository;
use Tests\TestCase;

class WalletRepositoryTest extends TestCase
{
    /** @var WalletRepository */
    private $walletRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletRepository = new WalletRepository();
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf(WalletRepository::class, $this->walletRepository);
    }

    /**
     * @test
     */
    public function shouldSaveOneWallet()
    {
        $user = factory(User::class)->create();
        $data = [
            'user_id' => $user->id,
            'amount'  => 0.00
        ];

        $user = $this->walletRepository->save($data);

        $this->assertInstanceOf(Wallet::class, $user);
        $this->assertCount(1, Wallet::all());
    }

    /**
     * @test
     */
    public function shouldFindOneWalletByUserId()
    {
        $fixture = factory(Wallet::class)->create();

        $wallet = $this->walletRepository->findByUserId($fixture->user_id);

        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertEquals($fixture->id, $wallet->id);
    }

    /**
     * @test
     */
    public function shouldNotFindWalletThenReturnsNull()
    {
        factory(Wallet::class)->create();

        $wallet = $this->walletRepository->findByUserId($this->faker->numerify('####'));

        $this->assertNull($wallet);
    }

    /**
     * @test
     */
    public function shouldUpdateWalletAmount()
    {
        $startAmount   = $this->faker->randomFloat(2, 3, 4);
        $depositAmount = $this->faker->randomFloat(2, 3, 4);
        $newAmount     = $startAmount + $depositAmount;
        $fixture       = factory(Wallet::class)->create(['amount' => $startAmount]);

        $wallet = $this->walletRepository->update($fixture, ['amount' => $newAmount]);

        $this->assertEquals($newAmount, $wallet->amount);
    }
}
