<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ThreadFilters extends Filters
{

    protected $filters = ['by','popular'];
    /**
     * @param $builder
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    protected function popular($username)
    {
        $this->builder->getQuery()->orders=[];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}
