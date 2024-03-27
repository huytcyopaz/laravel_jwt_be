<?php

namespace App\Repositories;

use App\Models\User;
use Exception;

class UserRepository
{
    /**
     * UserRepository Constructor
     */
    public function __construct(protected User $user) {
        //
    }

    /**
     * store a new user
     *
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        return $this->user->create($data);
    }
    /**
     * Find a user by email
     *
     * @param string $email
     * @return User
     * @throws Exception
     */
    // check email and password
    public function findByEmail(string $email): User
    {
        $user = $this->user->where('email', $email)->first();
        if (!$user) {
            throw new Exception('User not found');
        }
        return $user;
    }
    /**
     * Find a user by id
     *
     * @param int $id
     * @return User
     * @throws Exception
     */
    public function findById(int $id): User
    {
        $user = $this->user->find($id);
        if (!$user) {
            throw new Exception('User not found');
        }
        return $user;
    }
}
