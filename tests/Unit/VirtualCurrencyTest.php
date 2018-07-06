<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\VirtualCurrency;

class VirtualCurrencyTest extends TestCase
{
    use RefreshDatabase;

    private $user1;
    private $user2;

    public function setUp()
    {
        parent::setUp();
        $this->user1 = factory(User::class)->create();
        $this->user2 = factory(User::class)->create();
    }

    public function testCreditAmount()
    {
        $creditAmount = 0.25;

        $this->assertDatabaseMissing('virtual_currencies', [
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'credit' => $creditAmount
        ]);
        VirtualCurrency::creditAmount($this->user1->id, $this->user2->id, $creditAmount);
        $this->assertDatabaseHas('virtual_currencies', [
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'credit' => $creditAmount
        ]);
    }

    public function testDebitAmount()
    {
        $debitAmount = 0.35;

        $this->assertDatabaseMissing('virtual_currencies', [
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'debit' => $debitAmount
        ]);        
        VirtualCurrency::debitAmount($this->user1->id, $this->user2->id, $debitAmount);
        $this->assertDatabaseHas('virtual_currencies', [
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'debit' => $debitAmount
        ]);
    }

    public function testTrasferMoney()
    {
        $transferAmount = 0.99;
        $this->assertDatabaseMissing('virtual_currencies', [
            'source_user_id' => null,
            'destination_user_id' => $this->user1->id,
            'debit' => $transferAmount
        ]);
        $this->assertDatabaseMissing('virtual_currencies', [
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'credit' => $transferAmount
        ]);

        VirtualCurrency::transferMoney($this->user1->id, $this->user2->id, $transferAmount);

        $this->assertDatabaseHas('virtual_currencies', [
            'source_user_id' => null,
            'destination_user_id' => $this->user1->id,
            'debit' => $transferAmount
        ]);
        $this->assertDatabaseHas('virtual_currencies', [
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'credit' => $transferAmount
        ]);
    }

    public function testUserBalance()
    {
        factory(VirtualCurrency::class, 10)->create([
            'source_user_id' => $this->user1->id,
            'destination_user_id' => $this->user2->id,
            'credit' => 0.25
        ]);

        $user2Total = VirtualCurrency::userBalance($this->user2->id);
        $this->assertEquals(2.50, $user2Total);
    }
}
