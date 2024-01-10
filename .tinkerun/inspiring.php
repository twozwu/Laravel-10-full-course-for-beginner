<?php

use App\Models\User;

// use Illuminate\Foundation\Inspiring;

// Inspiring::quote();

$user = User::whereEmail('test@gmail.com')->first();

$user->email_verified_at;
