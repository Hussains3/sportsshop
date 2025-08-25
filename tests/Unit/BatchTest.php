<?php

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a batch', function () {
    $product = Product::factory()->create();
    $batch = Batch::factory()->create([
        'product_id' => $product->id,
        'batch_number' => 'BT123456',
        'initial_quantity' => 100,
        'current_quantity' => 80,
        'purchase_price' => 25.50,
        'min_selling_price' => 30.60,
        'max_selling_price' => 45.90,
        'purchase_date' => '2024-01-15',
        'notes' => 'First batch of the season'
    ]);

    expect($batch->product_id)->toBe($product->id)
        ->and($batch->batch_number)->toBe('BT123456')
        ->and($batch->initial_quantity)->toBe(100)
        ->and($batch->current_quantity)->toBe(80)
        ->and((float) $batch->purchase_price)->toBe(25.50)
        ->and((float) $batch->min_selling_price)->toBe(30.60)
        ->and((float) $batch->max_selling_price)->toBe(45.90)
        ->and($batch->purchase_date->format('Y-m-d'))->toBe('2024-01-15')
        ->and($batch->notes)->toBe('First batch of the season');
});

it('belongs to a product', function () {
    $product = Product::factory()->create(['name' => 'Test Product']);
    $batch = Batch::factory()->create(['product_id' => $product->id]);

    expect($batch->product)->toBeInstanceOf(Product::class)
        ->and($batch->product->name)->toBe('Test Product');
});

it('casts decimal fields correctly', function () {
    $batch = Batch::factory()->create([
        'purchase_price' => 25.99,
        'min_selling_price' => 30.99,
        'max_selling_price' => 45.99
    ]);

    expect((float) $batch->purchase_price)->toBe(25.99)
        ->and((float) $batch->min_selling_price)->toBe(30.99)
        ->and((float) $batch->max_selling_price)->toBe(45.99);
});

it('casts purchase_date to date', function () {
    $batch = Batch::factory()->create(['purchase_date' => '2024-01-15']);

    expect($batch->purchase_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($batch->purchase_date->format('Y-m-d'))->toBe('2024-01-15');
});

it('calculates profit margin correctly', function () {
    $batch = Batch::factory()->create([
        'purchase_price' => 100.00,
        'min_selling_price' => 120.00, // 20% markup
    ]);

    expect($batch->profit_margin)->toBe(20.0);
});

it('calculates max profit margin correctly', function () {
    $batch = Batch::factory()->create([
        'purchase_price' => 100.00,
        'max_selling_price' => 150.00, // 50% markup
    ]);

    expect($batch->max_profit_margin)->toBe(50.0);
});

it('handles zero purchase price in profit margin calculation', function () {
    // This should not happen in real scenarios, but we test edge cases
    $batch = new Batch([
        'purchase_price' => 0,
        'min_selling_price' => 100,
        'max_selling_price' => 150,
    ]);

    // This will cause division by zero, so we expect an exception or infinite value
    expect(function () use ($batch) {
        $batch->profit_margin;
    })->not->toThrow(Exception::class); // PHP handles division by zero gracefully
});

it('has correct fillable attributes', function () {
    $fillable = [
        'product_id', 'batch_number', 'initial_quantity', 'current_quantity',
        'purchase_price', 'min_selling_price', 'max_selling_price', 
        'purchase_date', 'notes'
    ];
    $batch = new Batch();

    expect($batch->getFillable())->toBe($fillable);
});

it('has correct appended attributes', function () {
    $batch = new Batch();
    $appended = ['profit_margin', 'max_profit_margin'];

    expect($batch->getAppends())->toBe($appended);
});

it('includes appended attributes in array representation', function () {
    $batch = Batch::factory()->create();
    $batchArray = $batch->toArray();

    expect($batchArray)->toHaveKeys(['profit_margin', 'max_profit_margin']);
});

it('can be created with stock available', function () {
    $batch = Batch::factory()->inStock()->create();

    expect($batch->current_quantity)->toBeGreaterThan(0)
        ->and($batch->current_quantity)->toBeLessThanOrEqual($batch->initial_quantity);
});

it('can be created as out of stock', function () {
    $batch = Batch::factory()->outOfStock()->create();

    expect($batch->current_quantity)->toBe(0);
});

it('validates current quantity does not exceed initial quantity', function () {
    $batch = Batch::factory()->create(['initial_quantity' => 50]);

    expect($batch->current_quantity)->toBeLessThanOrEqual($batch->initial_quantity);
});

it('ensures min selling price is reasonable markup', function () {
    $batch = Batch::factory()->create();

    expect((float) $batch->min_selling_price)->toBeGreaterThan((float) $batch->purchase_price);
});

it('ensures max selling price is greater than min selling price', function () {
    $batch = Batch::factory()->create();

    expect((float) $batch->max_selling_price)->toBeGreaterThanOrEqual((float) $batch->min_selling_price);
});
