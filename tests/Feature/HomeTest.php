<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * Testing Homepage.
     *
     * @return void
     */
    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->get('/');

        $response->assertSeeText('Welcome to Laravel!');
        $response->assertSeeText('This is the content ....');
        $response->assertStatus(200);
    }

        /**
     * Testing Contact page.
     *
     * @return void
     */
    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact!');
        $response->assertSeeText('This is the contact page ....');
        $response->assertStatus(200);
    }
}
