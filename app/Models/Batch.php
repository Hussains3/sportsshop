<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'initial_quantity',
        'current_quantity',
        'purchase_price',
        'min_selling_price',
        'max_selling_price',
        'purchase_date',
        'notes'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'min_selling_price' => 'decimal:2',
        'max_selling_price' => 'decimal:2',
        'purchase_date' => 'date'
    ];

    protected $appends = ['profit_margin', 'max_profit_margin'];

    public function getProfitMarginAttribute()
    {
        return ($this->min_selling_price - $this->purchase_price) / $this->purchase_price * 100;
    }

    public function getMaxProfitMarginAttribute()
    {
        return ($this->max_selling_price - $this->purchase_price) / $this->purchase_price * 100;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
