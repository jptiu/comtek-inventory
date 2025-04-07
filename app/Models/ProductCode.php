<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCode extends Model
{
    protected $fillable = [
        'product_id',
        'code',
        'type', // barcode, sku, etc.
        'is_primary',
        'is_sold'
    ];

    protected $casts = [
        'is_sold' => 'boolean'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function markAsSold()
    {
        $this->is_sold = true;
        $this->save();
    }

    public function markAsAvailable()
    {
        $this->is_sold = false;
        $this->save();
    }

    public function isAvailable()
    {
        return !$this->is_sold;
    }
}