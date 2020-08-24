<?php

namespace Tests\Http\Controllers\V1;

use App\Domain\Exceptions\TransactionErrorException;
use App\Domain\Models\Transaction;
use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use App\Domain\Services\TransactionService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TransactionControllerTest extends TestCase
{
    /** @var LegacyMockInterface */
    private $transactionServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionServiceMock = \Mockery::mock(TransactionService::class);

        app()->bind(TransactionService::class, function () {
            return $this->transactionServiceMock;
        });
    }

    /**
     * @test
     */
    public function shouldPostOneTransactionAndReturnOk()
    {
        $payerUser   = factory(User::class)->create();
        $payeeUser   = factory(User::class)->create();
        $payerWallet = factory(Wallet::class)->create(['user_id' => $payerUser->id]);
        $payeeWallet = factory(Wallet::class)->create(['user_id' => $payeeUser->id]);
        $fixture     = factory(Transaction::class)->make([
            'payer_wallet_id' => $payerWallet->id,
            'payee_wallet_id' => $payeeWallet->id,
        ]);
        $json        = [
            'value' => $this->faker->randomFloat(2, 2, 4),
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id,
        ];

        $this->transactionServiceMock->shouldReceive('makeTransaction')->with($json)->andReturn($fixture);

        $res = $this->call('POST', '/api/v1/transaction', $json);

        $res->assertStatus(HttpResponse::HTTP_OK);
    }

    /**
     * @test
     */
    public function shouldPostOneTransactionAndReturnUnprocessableEntity()
    {
        $payerUser   = factory(User::class)->create();
        $json        = [
            'value' => $this->faker->randomFloat(2, 2, 4),
            'payer' => $payerUser->id,
            'payee' => $payerUser->id,
        ];

        $res = $this->call('POST', '/api/v1/transaction', $json);

        $res->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function shouldPostOneTransactionAndReturnServiceUnavailable()
    {
        $payerUser   = factory(User::class)->create();
        $payeeUser   = factory(User::class)->create();
        $json        = [
            'value' => $this->faker->randomFloat(2, 2, 4),
            'payer' => $payerUser->id,
            'payee' => $payeeUser->id,
        ];

        $this->transactionServiceMock
            ->shouldReceive('makeTransaction')
            ->with($json)
            ->andThrow(TransactionErrorException::class);

        $res = $this->call('POST', '/api/v1/transaction', $json);

        $res->assertStatus(HttpResponse::HTTP_SERVICE_UNAVAILABLE);
    }
}
