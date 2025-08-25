<?php

use App\Models\SaleItem;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Batch;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a sale item', function () {
    $sale = Sale::factory()->create();
    $product = Product::factory()->create();
    $batch = Batch::factory()->create();
    
    $saleItem = SaleItem::factory()->create([
        'sale_id' => $sale->id,
        'product_id' => $product->id,
        'batch_id' => $batch->id,
        'quantity' => 3,
        'unit_price' => 25.50,
        'discount_amount' => 2.50,
        'subtotal' => 74.00 // (25.50 * 3) - 2.50
    ]);

    expect($saleItem->sale_id)->toBe($sale->id)
        ->and($saleItem->product_id)->toBe($product->id)
        ->and($saleItem->batch_id)->toBe($batch->id)
        ->and($saleItem->quantity)->toBe(3)
        ->and($saleItem->unit_price)->toBe(25.50)
        ->and($saleItem->discount_amount)->toBe(2.50)
        ->and($saleItem->subtotal)->toBe(74.00);
});

it('belongs to a sale', function () {
    $sale = Sale::factory()->create(['sale_number' => 'TEST-001']);
    $saleItem = SaleItem::factory()->create(['sale_id' => $sale->id]);

    expect($saleItem->sale)->toBeInstanceOf(Sale::class)
        ->and($saleItem->sale->sale_number)->toBe('TEST-001');
});

it('belongs to a product', function () {
    $product = Product::factory()->create(['name' => 'Test Product']);
    $saleItem = SaleItem::factory()->create(['product_id' => $product->id]);

    expect($saleItem->product)->toBeInstanceOf(Product::class)
        ->and($saleItem->product->name)->toBe('Test Product');
});

it('belongs to a batch', function () {
    $batch = Batch::factory()->create(['batch_number' => 'BT12345']);
    $saleItem = SaleItem::factory()->create(['batch_id' => $batch->id]);

    expect($saleItem->batch)->toBeInstanceOf(Batch::class)
        ->and($saleItem->batch->batch_number)->toBe('BT12345');
});

it('casts decimal fields correctly', function () {
    $saleItem = SaleItem::factory()->create([
        'unit_price' => 29.99,
        'discount_amount' => 4.99,
        'subtotal' => 85.98
    ]);

    expect($saleItem->unit_price)->toBe(29.99)
        ->and($saleItem->discount_amount)->toBe(4.99)
        ->and($saleItem->subtotal)->toBe(85.98);
});

it('formats subtotal with currency', function () {
    $saleItem = SaleItem::factory()->create(['subtotal' => 125.75]);

    expect($saleItem->formatted_subtotal)->toBe('৳125.75');
});

it('formats unit price with currency', function () {
    $saleItem = SaleItem::factory()->create(['unit_price' => 45.25]);

    expect($saleItem->formatted_unit_price)->toBe('৳45.25');
});

it('has correct fillable attributes', function () {
    $fillable = [
        'sale_id', 'product_id', 'batch_id', 'quantity',
        'unit_price', 'discount_amount', 'subtotal'
    ];
    $saleItem = new SaleItem();

    expect($saleItem->getFillable())->toBe($fillable);
});

it('can be created with no discount', function () {
    $saleItem = SaleItem::factory()->noDiscount()->create([
        'quantity' => 2,
        'unit_price' => 50.00
    ]);

    expect($saleItem->discount_amount)->toBe(0.00)
        ->and($saleItem->subtotal)->toBe(100.00); // 2 * 50.00
});

it('can be created with high discount', function () {
    $saleItem = SaleItem::factory()->highDiscount()->create([
        'quantity' => 4,
        'unit_price' => 100.00
    ]);

    $expectedDiscount = 400.00 * 0.25; // 25% discount
    $expectedSubtotal = 400.00 - $expectedDiscount;

    expect($saleItem->discount_amount)->toBe($expectedDiscount)
        ->and($saleItem->subtotal)->toBe($expectedSubtotal);
});

it('calculates subtotal correctly with discount', function () {
    $quantity = 3;
    $unitPrice = 20.00;
    $discountAmount = 10.00;
    $expectedSubtotal = ($quantity * $unitPrice) - $discountAmount;

    $saleItem = SaleItem::factory()->create([
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'discount_amount' => $discountAmount,
        'subtotal' => $expectedSubtotal
    ]);

    expect($saleItem->subtotal)->toBe($expectedSubtotal);
});

it('handles zero discount correctly', function () {
    $quantity = 2;
    $unitPrice = 35.00;
    $expectedSubtotal = $quantity * $unitPrice;

    $saleItem = SaleItem::factory()->create([
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'discount_amount' => 0.00,
        'subtotal' => $expectedSubtotal
    ]);

    expect($saleItem->discount_amount)->toBe(0.00)
        ->and($saleItem->subtotal)->toBe($expectedSubtotal);
});

it('validates quantity is positive', function () {
    $saleItem = SaleItem::factory()->create(['quantity' => 5]);

    expect($saleItem->quantity)->toBeGreaterThan(0);
});

it('validates unit price is positive', function () {
    $saleItem = SaleItem::factory()->create();

    expect($saleItem->unit_price)->toBeGreaterThan(0);
});

it('validates discount amount is not negative', function () {
    $saleItem = SaleItem::factory()->create();

    expect($saleItem->discount_amount)->toBeGreaterThanOrEqual(0);
});
