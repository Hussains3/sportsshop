<?php

use App\Models\Sale;
use App\Models\User;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a sale', function () {
    $user = User::factory()->create();
    $sale = Sale::factory()->create([
        'user_id' => $user->id,
        'subtotal' => 100.00,
        'tax_amount' => 15.00,
        'discount_amount' => 5.00,
        'total_amount' => 110.00,
        'amount_paid' => 115.00,
        'change_amount' => 5.00,
        'payment_method' => 'cash',
        'status' => 'completed',
        'notes' => 'Customer paid with exact change'
    ]);

    expect($sale->user_id)->toBe($user->id)
        ->and((float) $sale->subtotal)->toBe(100.00)
        ->and((float) $sale->tax_amount)->toBe(15.00)
        ->and((float) $sale->discount_amount)->toBe(5.00)
        ->and((float) $sale->total_amount)->toBe(110.00)
        ->and((float) $sale->amount_paid)->toBe(115.00)
        ->and((float) $sale->change_amount)->toBe(5.00)
        ->and($sale->payment_method)->toBe('cash')
        ->and($sale->status)->toBe('completed')
        ->and($sale->notes)->toBe('Customer paid with exact change');
});

it('auto-generates sale number on creation', function () {
    $sale = Sale::factory()->create();

    expect($sale->sale_number)->not->toBeEmpty()
        ->and($sale->sale_number)->toStartWith('SALE-' . date('Ymd') . '-');
});

it('can specify custom sale number', function () {
    $sale = Sale::factory()->create(['sale_number' => 'CUSTOM-001']);

    expect($sale->sale_number)->toBe('CUSTOM-001');
});

it('belongs to a user', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $sale = Sale::factory()->create(['user_id' => $user->id]);

    expect($sale->user)->toBeInstanceOf(User::class)
        ->and($sale->user->name)->toBe('John Doe');
});

it('has many sale items relationship', function () {
    $sale = Sale::factory()->create();
    SaleItem::factory()->count(3)->create(['sale_id' => $sale->id]);

    expect($sale->items)->toHaveCount(3)
        ->and($sale->items->first())->toBeInstanceOf(SaleItem::class);
});

it('casts decimal fields correctly', function () {
    $sale = Sale::factory()->create([
        'subtotal' => 99.99,
        'tax_amount' => 14.99,
        'discount_amount' => 9.99,
        'total_amount' => 104.99,
        'amount_paid' => 105.00,
        'change_amount' => 0.01
    ]);

    expect($sale->subtotal)->toBe(99.99)
        ->and($sale->tax_amount)->toBe(14.99)
        ->and($sale->discount_amount)->toBe(9.99)
        ->and($sale->total_amount)->toBe(104.99)
        ->and($sale->amount_paid)->toBe(105.00)
        ->and($sale->change_amount)->toBe(0.01);
});

it('formats total amount with currency', function () {
    $sale = Sale::factory()->create(['total_amount' => 125.50]);

    expect($sale->formatted_total)->toBe('৳125.50');
});

it('formats date correctly', function () {
    $sale = Sale::factory()->create(['created_at' => '2024-01-15 14:30:00']);
    $sale->created_at = \Carbon\Carbon::parse('2024-01-15 14:30:00');

    expect($sale->formatted_date)->toBe('Jan 15, 2024 2:30 PM');
});

it('has correct fillable attributes', function () {
    $fillable = [
        'sale_number', 'user_id', 'subtotal', 'tax_amount', 
        'discount_amount', 'total_amount', 'amount_paid', 
        'change_amount', 'payment_method', 'status', 'notes'
    ];
    $sale = new Sale();

    expect($sale->getFillable())->toBe($fillable);
});

it('can be created as completed', function () {
    $sale = Sale::factory()->completed()->create();

    expect($sale->status)->toBe('completed');
});

it('can be created as pending', function () {
    $sale = Sale::factory()->pending()->create();

    expect($sale->status)->toBe('pending');
});

it('can be created as cancelled', function () {
    $sale = Sale::factory()->cancelled()->create();

    expect($sale->status)->toBe('cancelled')
        ->and($sale->amount_paid)->toBe(0.00)
        ->and($sale->change_amount)->toBe(0.00);
});

it('generates unique sale numbers for same day', function () {
    $sale1 = Sale::factory()->create();
    $sale2 = Sale::factory()->create();

    expect($sale1->sale_number)->not->toBe($sale2->sale_number);
    
    // Both should be from today
    $today = date('Ymd');
    expect($sale1->sale_number)->toContain("SALE-{$today}-");
    expect($sale2->sale_number)->toContain("SALE-{$today}-");
});

it('accepts valid payment methods', function () {
    $paymentMethods = ['cash', 'card', 'mobile_money'];
    
    foreach ($paymentMethods as $method) {
        $sale = Sale::factory()->create(['payment_method' => $method]);
        expect($sale->payment_method)->toBe($method);
    }
});

it('accepts valid statuses', function () {
    $statuses = ['pending', 'completed', 'cancelled'];
    
    foreach ($statuses as $status) {
        $sale = Sale::factory()->create(['status' => $status]);
        expect($sale->status)->toBe($status);
    }
});
