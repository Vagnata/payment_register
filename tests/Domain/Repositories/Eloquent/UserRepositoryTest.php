<?php

namespace Tests\Domain\Repositories\Eloquent;

use App\Domain\Enuns\UserTypesEnum;
use App\Domain\Models\User;
use App\Domain\Repositories\Eloquent\UserRepository;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /** @var UserRepository */
    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    protected function assertPreConditions(): void
    {
        $this->assertInstanceOf(UserRepository::class, $this->userRepository);
    }

    /**
     * @test
     */
    public function shouldSaveOneUser()
    {
        $data = [
            'name'         => $this->faker->name,
            'cpf'          => $this->faker->cpf(false),
            'email'        => $this->faker->email,
            'cnpj'         => $this->faker->cnpj(false),
            'password'     => $this->faker->password,
            'user_type_id' => $this->faker->randomElement(UserTypesEnum::toArray())
        ];

        $user = $this->userRepository->save($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertCount(1, User::all());
    }

    /**
     * @test
     */
    public function shouldRetrieveOneUserById()
    {
        $fixture = factory(User::class)->create();

        $user = $this->userRepository->findById($fixture->id);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($fixture->id, $user->id);
    }

    /**
     * @test
     */
    public function shouldNotRetrieveAnyUser()
    {
        $user = $this->userRepository->findById($this->faker->randomDigit);

        $this->assertNull($user);
    }
}
