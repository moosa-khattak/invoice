<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceMeta extends Model
{
    //timestamps
    public $timestamps = false;
    protected $table = 'invoice_meta';

    protected $guarded = [];

    protected $casts = [
        'header_columns' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
