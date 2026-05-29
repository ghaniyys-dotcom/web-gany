<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetAdminPassword extends Command
{
    protected $signature = 'admin:set-password {password}';

    protected $description = 'Generate bcrypt hash for ADMIN_PASSWORD_HASH in .env';

    public function handle(): int
    {
        $hash = Hash::make($this->argument('password'));
        $this->line('Add this to your .env file:');
        $this->newLine();
        $this->line('ADMIN_PASSWORD_HASH='.$hash);

        return self::SUCCESS;
    }
}
