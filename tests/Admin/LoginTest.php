<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $admin = \MMC\Admin::first();
        $this
            ->actingAs($admin, 'admin')
            ->visit('/admin/')
            ->see('');
        ;
    }
}
