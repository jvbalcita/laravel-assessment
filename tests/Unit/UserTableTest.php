<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class)->in('Unit');

beforeEach(function () {
    $this->artisan('migrate'); // Ensure migrations are run
});

it('has expected columns in the users table', function () {
    $this->assertTrue(Schema::hasTable('users'), 'Users table does not exist');

    $expectedColumns = [
        'id',
        'prefix_name',
        'first_name',
        'middle_name',
        'last_name',
        'suffix_name',
        'user_name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'photo',
        'type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    foreach ($expectedColumns as $column) {
        $this->assertTrue(Schema::hasColumn('users', $column), "Column {$column} does not exist in the users table");
    }
});
