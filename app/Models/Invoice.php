<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    protected $guarded = [];

    protected $casts = [
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function meta(): HasOne
    {
        return $this->hasOne(InvoiceMeta::class);
    }

    public function getNotesAttribute()
    {
        return $this->meta?->notes;
    }
    public function getTermsAttribute()
    {
        return $this->meta?->terms;
    }
    public function getFromAttribute()
    {
        return $this->meta?->from;
    }
    public function getBillToAttribute()
    {
        return $this->meta?->bill_to;
    }
    public function getShipToAttribute()
    {
        return $this->meta?->ship_to;
    }
    public function getPaymentTermsAttribute()
    {
        return $this->meta?->payment_terms;
    }
    public function getPoNumberAttribute()
    {
        return $this->meta?->po_number;
    }
    public function getLogoPathAttribute()
    {
        return $this->meta?->logo_path;
    }
    public function getHeaderColumnsAttribute()
    {
        return $this->meta?->header_columns;
    }
}
