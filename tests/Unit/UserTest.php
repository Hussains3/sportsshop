<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('can create a user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->password)->not->toBeNull();
});

it('casts email_verified_at to datetime', function () {
    $user = User::factory()->create();

    expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('hashes password automatically', function () {
    $plainPassword = 'secret123';
    $user = User::factory()->create(['password' => $plainPassword]);

    expect($user->password)->not->toBe($plainPassword)
        ->and(Hash::check($plainPassword, $user->password))->toBeTrue();
});

it('hides password and remember_token from array', function () {
    $user = User::factory()->create();
    $userArray = $user->toArray();

    expect($userArray)->not->toHaveKey('password')
        ->and($userArray)->not->toHaveKey('remember_token');
});

it('can create unverified user', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

it('has unique email', function () {
    $email = 'unique@example.com';
    User::factory()->create(['email' => $email]);

    expect(function () use ($email) {
        User::factory()->create(['email' => $email]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('requires name, email and password', function () {
    expect(function () {
        User::create([]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('has factory', function () {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->exists)->toBeTrue();
});
