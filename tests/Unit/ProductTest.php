<?php

use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Batch;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a product', function () {
    $subCategory = SubCategory::factory()->create();
    $product = Product::factory()->create([
        'sub_category_id' => $subCategory->id,
        'name' => 'Nike Football Boots',
        'slug' => 'nike-football-boots',
        'description' => 'Professional football boots for athletes',
        'sku' => 'NK-FB-001',
        'is_active' => true,
        'is_featured' => false,
    ]);

    expect($product->name)->toBe('Nike Football Boots')
        ->and($product->slug)->toBe('nike-football-boots')
        ->and($product->description)->toBe('Professional football boots for athletes')
        ->and($product->sku)->toBe('NK-FB-001')
        ->and($product->is_active)->toBeTrue()
        ->and($product->is_featured)->toBeFalse()
        ->and($product->sub_category_id)->toBe($subCategory->id);
});

it('belongs to a subcategory', function () {
    $subCategory = SubCategory::factory()->create(['name' => 'Football Boots']);
    $product = Product::factory()->create(['sub_category_id' => $subCategory->id]);

    expect($product->subcategory)->toBeInstanceOf(SubCategory::class)
        ->and($product->subcategory->name)->toBe('Football Boots');
});

it('has category through subcategory relationship', function () {
    $category = Category::factory()->create(['name' => 'Football']);
    $subCategory = SubCategory::factory()->create(['category_id' => $category->id]);
    $product = Product::factory()->create(['sub_category_id' => $subCategory->id]);

    expect($product->category)->toBeInstanceOf(Category::class)
        ->and($product->category->name)->toBe('Football');
});

it('has many batches relationship', function () {
    $product = Product::factory()->create();
    Batch::factory()->count(3)->create(['product_id' => $product->id]);

    expect($product->batches)->toHaveCount(3)
        ->and($product->batches->first())->toBeInstanceOf(Batch::class);
});

it('casts boolean attributes correctly', function () {
    $product = Product::factory()->create([
        'is_active' => 1,
        'is_featured' => 0,
    ]);

    expect($product->is_active)->toBeBool()->toBeTrue()
        ->and($product->is_featured)->toBeBool()->toBeFalse();
});

it('calculates current stock from batches', function () {
    $product = Product::factory()->create();
    Batch::factory()->create(['product_id' => $product->id, 'current_quantity' => 50]);
    Batch::factory()->create(['product_id' => $product->id, 'current_quantity' => 30]);
    Batch::factory()->create(['product_id' => $product->id, 'current_quantity' => 20]);

    expect($product->current_stock)->toBe(100);
});

it('returns zero stock when no batches exist', function () {
    $product = Product::factory()->create();

    expect($product->current_stock)->toBe(0);
});

it('calculates minimum price from available batches', function () {
    $product = Product::factory()->create();
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 10,
        'min_selling_price' => 25.00
    ]);
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 5,
        'min_selling_price' => 20.00
    ]);
    // Out of stock batch should be ignored
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 0,
        'min_selling_price' => 15.00
    ]);

    expect((float) $product->min_price)->toBe(20.00);
});

it('calculates maximum price from available batches', function () {
    $product = Product::factory()->create();
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 10,
        'max_selling_price' => 35.00
    ]);
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 5,
        'max_selling_price' => 40.00
    ]);
    // Out of stock batch should be ignored
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 0,
        'max_selling_price' => 50.00
    ]);

    expect((float) $product->max_price)->toBe(40.00);
});

it('returns null for min and max price when no stock available', function () {
    $product = Product::factory()->create();
    Batch::factory()->create([
        'product_id' => $product->id,
        'current_quantity' => 0,
        'min_selling_price' => 20.00,
        'max_selling_price' => 30.00
    ]);

    expect($product->min_price)->toBeNull()
        ->and($product->max_price)->toBeNull();
});

it('has correct fillable attributes', function () {
    $fillable = [
        'sub_category_id', 'name', 'slug', 'description', 
        'sku', 'image', 'is_active', 'is_featured'
    ];
    $product = new Product();

    expect($product->getFillable())->toBe($fillable);
});

it('has correct appended attributes', function () {
    $product = new Product();
    $appended = ['current_stock', 'min_price', 'max_price'];

    expect($product->getAppends())->toBe($appended);
});

it('includes appended attributes in array representation', function () {
    $product = Product::factory()->create();
    $productArray = $product->toArray();

    expect($productArray)->toHaveKeys(['current_stock', 'min_price', 'max_price']);
});

it('can be created as active', function () {
    $product = Product::factory()->active()->create();

    expect($product->is_active)->toBeTrue();
});

it('can be created as featured', function () {
    $product = Product::factory()->featured()->create();

    expect($product->is_featured)->toBeTrue()
        ->and($product->is_active)->toBeTrue(); // Featured products should be active
});
