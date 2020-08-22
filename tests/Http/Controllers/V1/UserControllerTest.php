<?php

namespace Tests\Http\Controllers\V1;

use App\Domain\Enum\UserTypesEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function shouldInsertOneCommonUser()
    {
        $this->assertTrue(true);
        $json = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf,
            'email'        => $this->faker->email,
            'cnpj'         => null,
            'password'     => $this->faker->password,
            'user_type_id' => UserTypesEnum::COMMON
        ];

        $res = $this->call('POST ', '/v1/user', $json);

        $res->assertStatus(200)
            ->assertJson(['message' => 'Usuário inserido com sucesso!']);
    }

    /**
     * @test
     */
    public function shouldInsertOneMerchantUser()
    {
        $this->assertTrue(true);
        $json = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf,
            'email'        => $this->faker->email,
            'cnpj'         => $this->faker->cnpj,
            'password'     => $this->faker->password,
            'user_type_id' => UserTypesEnum::MERCHANT
        ];

        $res = $this->call('POST ', '/v1/user', $json);

        $res->assertStatus(200)
            ->assertJson(['message' => 'Usuário inserido com sucesso!']);
    }
}
