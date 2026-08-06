<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class TrackUserLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;

        // Save session information in the `user_sessions` table
        DB::table('user_sessions')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'last_activity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
