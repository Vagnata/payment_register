<?php

namespace Tests\Http\Controllers\V1;

use App\Domain\Enuns\UserTypesEnum;
use App\Domain\Exceptions\FailUserInsertionException;
use App\Domain\Models\User;
use App\Domain\Services\UserService;
use Mockery\LegacyMockInterface;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserControllerTest extends TestCase
{
    /** @var LegacyMockInterface */
    private $userServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userServiceMock = \Mockery::mock(UserService::class);

        app()->bind(UserService::class, function () {
            return $this->userServiceMock;
        });
    }

    /**
     * @test
     */
    public function shouldInsertOneCommonUser()
    {
        $json    = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf(false),
            'email'        => $this->faker->email,
            'cnpj'         => null,
            'password'     => $this->faker->password,
            'user_type_id' => UserTypesEnum::COMMON
        ];
        $fixture = factory(User::class)->make($json);
        $this->userServiceMock
            ->shouldReceive('addUser')
            ->with($json)
            ->andReturn($fixture);

        $res = $this->call('POST', '/api/v1/user', $json);

        $res->assertStatus(HttpResponse::HTTP_OK);
    }

    /**
     * @test
     */
    public function shouldInsertOneMerchantUser()
    {
        $json    = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf(false),
            'email'        => $this->faker->email,
            'cnpj'         => null,
            'password'     => $this->faker->password,
            'user_type_id' => UserTypesEnum::MERCHANT
        ];
        $fixture = factory(User::class)->make($json);
        $this->userServiceMock
            ->shouldReceive('addUser')
            ->with($json)
            ->andReturn($fixture);

        $res = $this->call('POST', '/api/v1/user', $json);

        $res->assertStatus(HttpResponse::HTTP_OK);
    }

    /**
     * @test
     */
    public function shouldReturnInternalServerError()
    {
        $json = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf,
            'email'        => $this->faker->email,
            'cnpj'         => $this->faker->cnpj,
            'password'     => $this->faker->password,
            'user_type_id' => $this->faker->randomElement(UserTypesEnum::toArray())
        ];
        $this->userServiceMock
            ->shouldReceive('addUser')
            ->with($json)
            ->andThrow(FailUserInsertionException::class);

        $res = $this->call('POST', '/api/v1/user', $json);

        $res->assertStatus(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @test
     */
    public function shouldReturnUnprocessableEntity()
    {
        $json = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->randomDigit,
            'email'        => $this->faker->email,
            'cnpj'         => $this->faker->cnpj,
            'password'     => $this->faker->password,
            'user_type_id' => $this->faker->randomElement(UserTypesEnum::toArray())
        ];

        $res = $this->call('POST', '/api/v1/user', $json);

        $res->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
