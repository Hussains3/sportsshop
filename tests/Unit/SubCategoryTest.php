<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a subcategory', function () {
    $category = Category::factory()->create();
    $subCategory = SubCategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Football Boots',
        'slug' => 'football-boots',
        'description' => 'Professional football boots',
        'is_active' => true,
    ]);

    expect($subCategory->name)->toBe('Football Boots')
        ->and($subCategory->slug)->toBe('football-boots')
        ->and($subCategory->description)->toBe('Professional football boots')
        ->and($subCategory->is_active)->toBeTrue()
        ->and($subCategory->category_id)->toBe($category->id);
});

it('belongs to a category', function () {
    $category = Category::factory()->create(['name' => 'Football']);
    $subCategory = SubCategory::factory()->create(['category_id' => $category->id]);

    expect($subCategory->category)->toBeInstanceOf(Category::class)
        ->and($subCategory->category->name)->toBe('Football')
        ->and($subCategory->category->id)->toBe($category->id);
});

it('has many products relationship', function () {
    $subCategory = SubCategory::factory()->create();
    Product::factory()->count(5)->create(['sub_category_id' => $subCategory->id]);

    expect($subCategory->products)->toHaveCount(5)
        ->and($subCategory->products->first())->toBeInstanceOf(Product::class);
});

it('casts is_active to boolean', function () {
    $subCategory = SubCategory::factory()->create(['is_active' => 0]);

    expect($subCategory->is_active)->toBeBool()
        ->and($subCategory->is_active)->toBeFalse();
});

it('has fillable attributes', function () {
    $fillable = ['category_id', 'name', 'slug', 'description', 'is_active'];
    $subCategory = new SubCategory();

    expect($subCategory->getFillable())->toBe($fillable);
});

it('can retrieve products through relationship', function () {
    $subCategory = SubCategory::factory()->create();
    $product = Product::factory()->create([
        'sub_category_id' => $subCategory->id,
        'name' => 'Nike Football Boots'
    ]);

    $retrievedProduct = $subCategory->products()->where('name', 'Nike Football Boots')->first();

    expect($retrievedProduct)->not->toBeNull()
        ->and($retrievedProduct->name)->toBe('Nike Football Boots')
        ->and($retrievedProduct->sub_category_id)->toBe($subCategory->id);
});

it('has factory with proper category relationship', function () {
    $subCategory = SubCategory::factory()->create();

    expect($subCategory)->toBeInstanceOf(SubCategory::class)
        ->and($subCategory->exists)->toBeTrue()
        ->and($subCategory->category)->toBeInstanceOf(Category::class)
        ->and($subCategory->category_id)->not->toBeNull();
});

it('can be created with specific category', function () {
    $category = Category::factory()->create(['name' => 'Tennis']);
    $subCategory = SubCategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Tennis Rackets'
    ]);

    expect($subCategory->category->name)->toBe('Tennis')
        ->and($subCategory->name)->toBe('Tennis Rackets');
});
