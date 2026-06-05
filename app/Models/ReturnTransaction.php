<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'lending_transaction_id',
        'quantity',
        'returned_at',
        'condition',
        'remarks',
        'processed_by',
        'payment_status', // 🔥 Idinagdag para sa damage assessment status
        'penalty_amount',  // 🔥 Idinagdag para sa paniningil ng penalty sa sirang gamit
    ];

    // Sa Laravel 11, mas maganda kung gagamitin ang bagong array structural format:
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'returned_at' => 'datetime', // 🔥 Ginawang 'datetime' mula 'date' para hindi mag-error ang ->format() sa Export Engine
            'penalty_amount' => 'decimal:2', // 🔥 Tinitiyak na may dalawang decimal places ang pera (.00)
        ];
    }

    /**
     * Balik sa LendingTransaction kung saan nagmula ang return entry na ito.
     */
    public function lending()
    {
        return $this->belongsTo(LendingTransaction::class, 'lending_transaction_id');
    }

    /**
     * Ang user/staff na nag-asikaso ng pagbabalik ng aytem.
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
