<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'sub_category_id',
        'name',
        'slug',
        'description',
        'sku',
        'image',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean'
    ];

    protected $appends = ['current_stock', 'min_price', 'max_price'];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function getCurrentStockAttribute()
    {
        return $this->batches()->sum('current_quantity');
    }

    public function getMinPriceAttribute()
    {
        $batch = $this->batches()
            ->where('current_quantity', '>', 0)
            ->orderBy('min_selling_price')
            ->first();
        return $batch ? $batch->min_selling_price : null;
    }

    public function getMaxPriceAttribute()
    {
        $batch = $this->batches()
            ->where('current_quantity', '>', 0)
            ->orderBy('max_selling_price', 'desc')
            ->first();
        return $batch ? $batch->max_selling_price : null;
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            SubCategory::class,
            'id', // Foreign key on subcategories table...
            'id', // Foreign key on categories table...
            'sub_category_id', // Local key on products table...
            'category_id' // Local key on subcategories table...
        );
    }
}
