<?php

use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a complete sale with multiple items', function () {
    // Create test data
    $user = User::factory()->create();
    $category = Category::factory()->create(['name' => 'Sports']);
    $subCategory = SubCategory::factory()->create(['category_id' => $category->id]);
    
    $product1 = Product::factory()->create(['sub_category_id' => $subCategory->id, 'name' => 'Football']);
    $product2 = Product::factory()->create(['sub_category_id' => $subCategory->id, 'name' => 'Basketball']);
    
    $batch1 = Batch::factory()->create([
        'product_id' => $product1->id,
        'current_quantity' => 10,
        'min_selling_price' => 25.00
    ]);
    $batch2 = Batch::factory()->create([
        'product_id' => $product2->id,
        'current_quantity' => 5,
        'min_selling_price' => 35.00
    ]);
    
    // Create sale
    $sale = Sale::factory()->create([
        'user_id' => $user->id,
        'subtotal' => 95.00,
        'tax_amount' => 14.25,
        'discount_amount' => 5.00,
        'total_amount' => 104.25,
        'amount_paid' => 105.00,
        'change_amount' => 0.75,
        'status' => 'completed'
    ]);
    
    // Create sale items
    $saleItem1 = SaleItem::factory()->create([
        'sale_id' => $sale->id,
        'product_id' => $product1->id,
        'batch_id' => $batch1->id,
        'quantity' => 2,
        'unit_price' => 25.00,
        'discount_amount' => 0,
        'subtotal' => 50.00
    ]);
    
    $saleItem2 = SaleItem::factory()->create([
        'sale_id' => $sale->id,
        'product_id' => $product2->id,
        'batch_id' => $batch2->id,
        'quantity' => 1,
        'unit_price' => 35.00,
        'discount_amount' => 0,
        'subtotal' => 35.00
    ]);
    
    // Assertions
    expect($sale->items)->toHaveCount(2)
        ->and($sale->user->id)->toBe($user->id)
        ->and((float) $sale->total_amount)->toBe(104.25)
        ->and($sale->status)->toBe('completed');
        
    expect($saleItem1->product->name)->toBe('Football')
        ->and($saleItem1->quantity)->toBe(2)
        ->and((float) $saleItem1->subtotal)->toBe(50.00);
        
    expect($saleItem2->product->name)->toBe('Basketball')
        ->and($saleItem2->quantity)->toBe(1)
        ->and((float) $saleItem2->subtotal)->toBe(35.00);
});

it('calculates product stock correctly across multiple batches', function () {
    $product = Product::factory()->create();
    
    // Create multiple batches for the same product
    Batch::factory()->create(['product_id' => $product->id, 'current_quantity' => 10]);
    Batch::factory()->create(['product_id' => $product->id, 'current_quantity' => 15]);
    Batch::factory()->create(['product_id' => $product->id, 'current_quantity' => 5]);
    
    expect($product->current_stock)->toBe(30);
});

it('calculates product pricing correctly from multiple batches', function () {
    $product = Product::factory()->create();
    
    // Create batches with different prices
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 10,
        'min_selling_price' => 20.00,
        'max_selling_price' => 30.00
    ]);
    
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 5,
        'min_selling_price' => 25.00,
        'max_selling_price' => 35.00
    ]);
    
    // Out of stock batch should be ignored
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 0,
        'min_selling_price' => 15.00,
        'max_selling_price' => 40.00
    ]);
    
    expect((float) $product->min_price)->toBe(20.00)
        ->and((float) $product->max_price)->toBe(35.00);
});

it('auto-generates unique sale numbers', function () {
    $sale1 = Sale::factory()->create();
    $sale2 = Sale::factory()->create();
    $sale3 = Sale::factory()->create();
    
    $saleNumbers = [$sale1->sale_number, $sale2->sale_number, $sale3->sale_number];
    
    expect(count($saleNumbers))->toBe(count(array_unique($saleNumbers)))
        ->and($sale1->sale_number)->toStartWith('SALE-' . date('Ymd'))
        ->and($sale2->sale_number)->toStartWith('SALE-' . date('Ymd'))
        ->and($sale3->sale_number)->toStartWith('SALE-' . date('Ymd'));
});

it('maintains referential integrity in sales process', function () {
    // Create hierarchical data
    $category = Category::factory()->create(['name' => 'Electronics']);
    $subCategory = SubCategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Phones'
    ]);
    $product = Product::factory()->create([
        'sub_category_id' => $subCategory->id,
        'name' => 'Smartphone'
    ]);
    $batch = Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 50
    ]);
    
    $user = User::factory()->create();
    $sale = Sale::factory()->create(['user_id' => $user->id]);
    
    $saleItem = SaleItem::factory()->create([
        'sale_id' => $sale->id,
        'product_id' => $product->id,
        'batch_id' => $batch->id
    ]);
    
    // Test relationships work correctly
    expect($saleItem->sale->id)->toBe($sale->id)
        ->and($saleItem->product->id)->toBe($product->id)
        ->and($saleItem->batch->id)->toBe($batch->id)
        ->and($saleItem->product->subcategory->id)->toBe($subCategory->id)
        ->and($saleItem->product->category->id)->toBe($category->id)
        ->and($sale->user->id)->toBe($user->id);
});
