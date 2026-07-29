<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'stock_quantity',
        'is_available',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock_quantity', '>', 0);
    }
}
