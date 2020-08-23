<?php

namespace Tests\Http\Controllers\V1;

use App\Domain\Models\Wallet;
use App\Domain\Services\WalletService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class WalletControllerTest extends TestCase
{
    /** @var LegacyMockInterface */
    private $walletServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletServiceMock = \Mockery::mock(WalletService::class);

        app()->bind(WalletService::class, function () {
            return $this->walletServiceMock;
        });
    }

    /**
     * @test
     */
    public function shouldAddCreditsToOneWallet()
    {
        $fixture         = factory(Wallet::class)->make();
        $depositAmount   = $this->faker->randomFloat(2, 2, 4);
        $json            = [
            'user_id' => $fixture->user_id,
            'amount'  => $depositAmount,
        ];
        $fixture->amount = $fixture->amount + $depositAmount;

        $this->walletServiceMock->shouldReceive('addCreditToWallet')->with($json)->andReturn($fixture);

        $res = $this->call('PUT', '/api/v1/wallet', $json);

        $res->assertStatus(HttpResponse::HTTP_OK);
    }

    /**
     * @test
     */
    public function shouldReturnUnprocessableEntity()
    {
        $json = [
            'user_id' => $this->faker->randomDigit,
            'amount'  => $this->faker->randomFloat(2, 2, 4),
        ];

        $res = $this->call('PUT', '/api/v1/wallet', $json);

        $res->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
