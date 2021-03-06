<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase; // empty the test DB

    /***************************************************************************/

    /**
     * Populate test DB with dummy data.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /***************************************************************************/

    /**
     * Test that guest user can see user registration.
     */
    public function test_see_user_registration()
    {
        $response = $this->get('register')
                          ->assertStatus(200);
    }

    /***************************************************************************/

    /**
     * Test that guest user can register.
     */
    public function test_user_registration()
    {
        // Prevent validation error on captcha
        /*\NoCaptcha::shouldReceive('verifyResponse')
                ->once()
                ->andReturn(true);*/

        // Post a data to register a user
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'country_id' => $this->faker->numberBetween($min = 1, $max = 253),
            'description' => $this->faker->paragraph,
            'accept_terms' => 1,
            //'g-recaptcha-response' => '1', // Simulates Captcha clicked
            'recaptcha_sum_1' => 5,
            'random_number_1' => 1,
            'random_number_2' => 4,
        ];

        $response = $this
                ->followingRedirects()
                ->post('register', $data);

        // Assert in database
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $response
                ->assertStatus(200)
                ->assertSee(__('auth.successfully_registered'));
    }
}
