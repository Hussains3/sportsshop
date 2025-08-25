<?php

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a category', function () {
    $category = Category::factory()->create([
        'name' => 'Football',
        'slug' => 'football',
        'description' => 'Football related products',
        'is_active' => true,
    ]);

    expect($category->name)->toBe('Football')
        ->and($category->slug)->toBe('football')
        ->and($category->description)->toBe('Football related products')
        ->and($category->is_active)->toBeTrue();
});

it('casts is_active to boolean', function () {
    $category = Category::factory()->create(['is_active' => 1]);

    expect($category->is_active)->toBeBool()
        ->and($category->is_active)->toBeTrue();
});

it('has many subcategories relationship', function () {
    $category = Category::factory()->create();
    SubCategory::factory()->count(3)->create(['category_id' => $category->id]);

    expect($category->subCategories)->toHaveCount(3)
        ->and($category->subCategories->first())->toBeInstanceOf(SubCategory::class);
});

it('can retrieve subcategories through relationship', function () {
    $category = Category::factory()->create();
    $subCategory = SubCategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'Football Boots'
    ]);

    $retrievedSubCategory = $category->subCategories()->where('name', 'Football Boots')->first();

    expect($retrievedSubCategory)->not->toBeNull()
        ->and($retrievedSubCategory->name)->toBe('Football Boots')
        ->and($retrievedSubCategory->category_id)->toBe($category->id);
});

it('has fillable attributes', function () {
    $fillable = ['name', 'slug', 'description', 'is_active'];
    $category = new Category();

    expect($category->getFillable())->toBe($fillable);
});

it('has factory', function () {
    $category = Category::factory()->create();

    expect($category)->toBeInstanceOf(Category::class)
        ->and($category->exists)->toBeTrue()
        ->and($category->name)->not->toBeEmpty()
        ->and($category->slug)->not->toBeEmpty();
});

it('can be created with specific attributes', function () {
    $attributes = [
        'name' => 'Basketball',
        'slug' => 'basketball',
        'description' => 'Basketball equipment and gear',
        'is_active' => false,
    ];

    $category = Category::factory()->create($attributes);

    expect($category->name)->toBe($attributes['name'])
        ->and($category->slug)->toBe($attributes['slug'])
        ->and($category->description)->toBe($attributes['description'])
        ->and($category->is_active)->toBe($attributes['is_active']);
});
