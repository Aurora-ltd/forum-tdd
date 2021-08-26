<?php
namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];
    /**
     * fetch the user_id for the given username
     *
     * @param [type] $builder
     * @param [type] $username
     * @return void
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}