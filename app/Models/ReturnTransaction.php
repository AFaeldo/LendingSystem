<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['lending_transaction_id','quantity','returned_at','condition','remarks','processed_by'];

    public function lending()
    {
        return $this->belongsTo(LendingTransaction::class);
    }
}
