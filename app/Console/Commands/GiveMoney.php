<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\User;
use App\VirtualCurrency;

class GiveMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:give
                            {user? : The ID of the user to give money to}
                            {--amount=0.25 : The amount of money to give}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give money to all users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $amount = $this->option('amount');

        if ($userId) {
            $this->line("Giving user $userId a credit of $amount");
            $this->giveUserMoney($userId, $amount);
        } else {
            $this->giveAllUsersMoney($amount);
        }
    }

    private function giveAllUsersMoney($amount, $quiet = false)
    {
        $bar = $this->output->createProgressBar(User::count());
        $bar->setFormat(' %current%/%max% [%bar%] %message%');
        $bar->setMessage('Giving money to users');
        $bar->start();
    
        User::chunk(200, function($users) use($bar, $amount) {
            foreach ($users as $user) {
                $bar->setMessage("Giving user $user->email a credit of $amount");
                $this->giveUserMoney($user->id, $amount);
                $bar->advance();
            }
        });

        $bar->setMessage('Complete');
        $bar->finish();
        $this->line('');
    }

    private function giveUserMoney($userId, $amount)
    {
        VirtualCurrency::creditAmount(null, $userId, $amount);
    }
}
