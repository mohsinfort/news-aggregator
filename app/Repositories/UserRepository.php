<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Carbon;

class UserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createUser(string $name, string $email, string $password)
    {
        return $this->user->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'email_verified_at' => Carbon::now(), // TODO: email should be verified by send link at given email address
        ]);
    }

    public function getUserByEmail(string $email)
    {
        return $this->user->where('email', $email)->first();
    }
}