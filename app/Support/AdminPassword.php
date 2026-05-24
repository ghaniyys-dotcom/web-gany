<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Hash;

class AdminPassword
{
    public static function verify(string $password): bool
    {
        $site = SiteSetting::query()->first();

        if ($site?->admin_password_hash) {
            return Hash::check($password, $site->admin_password_hash);
        }

        $envHash = env('ADMIN_PASSWORD_HASH');

        if ($envHash) {
            return Hash::check($password, $envHash);
        }

        $plain = env('ADMIN_PASSWORD');

        if ($plain) {
            return hash_equals($plain, $password);
        }

        return false;
    }

    public static function update(string $password): void
    {
        SiteSetting::current()->update([
            'admin_password_hash' => Hash::make($password),
        ]);
    }
}
