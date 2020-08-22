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
}
