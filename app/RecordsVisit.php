<?php


namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordsVisit
{

    public function recordVisit()
    {
        Redis::incr("threads.{$this->id}.visits");

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    protected function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }
}
