<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_getToken_route_exists()
    {
        $response = $this->json('post', 'api/v1/getToken');

        $actual = 404;
        $this->assertNotEquals($response->getStatusCode(), $actual);
    }

    /**
     * @dataProvider invalid_request_body_parameters
     *
     * @param  array  $data
     * @param  int  $statusCode
     * @return void
     */
    public function test_request_body_is_valid(array $data, int $statusCode): void
    {
        $response = $this->json('post', 'api/v1/getToken', $data);

        $response->assertStatus($statusCode);
    }

    /**
     * @dataProvider valid_correct_request_body_parameters
     * @param  array  $data
     * @return void
     */
    public function test_response_body_structure(array $data): void
    {
        $response = $this->json('post', 'api/v1/getToken', $data);

        $response->assertJsonStructure([
            "success",
            "code",
            "token",
        ]);
    }

    public function invalid_request_body_parameters(): array
    {
        return [
            [
                'data' => [],
                'statusCode' => 422,
            ],
            [
                'data' => ['Username' => 'myUsername',],
                'statusCode' => 422,
            ],
            [
                'data' => ['Password' => 'password',],
                'statusCode' => 422,
            ],
            [
                'data' => [
                    'Username' => 'myUsername',
                    'Password' => 'password',
                ],
                'statusCode' => 401,
            ],
        ];
    }

    public function valid_correct_request_body_parameters(): array
    {
        return [
            [
                'data' => [
                    'Username' => 'Username',
                    'Password' => 'somerandompassword!!123',
                ],
            ],
        ];
    }

    public function wrong_username_password(): array
    {
        return [
            [
                'data' => [
                    'Username' => 'wrong_username',
                    'Password' => 'wrong_password',
                ],
            ],
        ];
    }
}
