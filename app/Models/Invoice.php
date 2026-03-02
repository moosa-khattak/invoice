<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
   protected $guarded = [];
    protected $casts = [
        'header_columns' => 'array',
        'items' => 'array',
        'date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];
}
