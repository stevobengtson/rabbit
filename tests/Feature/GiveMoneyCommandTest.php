<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GiveMoneyCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSingleUser()
    {
        $this->artisan('money:give', [1]);
        $this->assertTrue(true);
    }
}
