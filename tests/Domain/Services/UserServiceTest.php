<?php

namespace Tests\Domain\Services;

use App\Domain\Enuns\UserTypesEnum;
use App\Domain\Exceptions\FailUserInsertionException;
use App\Domain\Models\User;
use App\Domain\Models\Wallet;
use App\Domain\Repositories\Eloquent\UserRepository;
use App\Domain\Repositories\Eloquent\WalletRepository;
use App\Domain\Services\UserService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /** @var UserService */
    private $userService;

    /** @var LegacyMockInterface */
    private $userRepositoryMock;

    /** @var LegacyMockInterface */
    private $walletRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = \Mockery::mock(UserRepository::class);
        $this->walletRepositoryMock = \Mockery::mock(WalletRepository::class);
        $this->userService        = new UserService($this->userRepositoryMock, $this->walletRepositoryMock);
    }

    /**
     * @test
     */
    public function shouldAddOneUser()
    {
        $fixture = factory(User::class)->create($this->userFixtureData());
        $wallet  = factory(Wallet::class)->create(['user_id' => $fixture->id]);
        $this->userRepositoryMock->shouldReceive('save')->andReturn($fixture);
        $this->walletRepositoryMock->shouldReceive('save')->andReturn($wallet);

        $user = $this->userService->addUser($this->userFixtureData());

        $this->assertInstanceOf(User::class, $user);
        $this->assertCount(1, User::all());
    }

    /**
     * @test
     */
    public function shouldThrowFailUserInsertionException()
    {
        $this->userRepositoryMock->shouldReceive('save')->andThrow(\Exception::class);

        $this->expectException(FailUserInsertionException::class);
        $this->expectExceptionMessage('Falha ao inserir usuário, tente novamente mais tarde');

        $this->userService->addUser($this->userFixtureData());
    }

    private function userFixtureData(): array
    {
        return [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf(false),
            'email'        => $this->faker->email,
            'cnpj'         => $this->faker->cnpj(false),
            'password'     => $this->faker->password,
            'user_type_id' => $this->faker->randomElement(UserTypesEnum::toArray())
        ];
    }
}
