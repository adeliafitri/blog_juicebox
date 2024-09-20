<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\SendWelcomeEmailJob;

class SendWelcomeEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-welcome {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome email to a specific user via job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        // dd($userId);
        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found');
            return;
        }

        // Dispatch the welcome email job
        SendWelcomeEmailJob::dispatch($user);

        $this->info('Welcome email has been sent to user: ' . $user->email);
    }
}
