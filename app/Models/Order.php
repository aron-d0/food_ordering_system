<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['Pending', 'Preparing', 'Completed', 'Cancelled'];

    protected $fillable = [
        'user_id',
        'food_id',
        'quantity',
        'total_price',
        'status',
        'order_date',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'total_price' => 'decimal:2',
            'order_date' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
