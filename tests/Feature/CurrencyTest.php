<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class CurrencyTest extends TestCase
{
    /**
     * Assert we can get a response of currencies
     *
     * @return void
     */
    public function test_we_can_get_currencies(): void
    {
		Http::fake([
			''
		]);
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
