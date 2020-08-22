<?php

namespace Tests;

use Faker\Generator;
use Faker\Provider\pt_BR\Person;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var Generator */
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = new Generator();
        $this->faker->addProvider(new Person($this->faker));
        Artisan::call('migrate');
        $this->seed();
    }

    public function createApplication()
    {
        putenv('DB_DATABASE=payment_register_test');

        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }


    public function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
