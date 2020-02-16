<?php

namespace Tests\Feature;

use App\Inspections\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{

     /** @test */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Inncocent reply here'));

    }

    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();

        $this->expectException('Exception');
        $spam->detect('Hello world qqqqqqqqqqqqq');

    }
}

