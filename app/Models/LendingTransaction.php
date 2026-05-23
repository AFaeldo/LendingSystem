<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LendingTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['borrower_id','inventory_item_id','quantity','borrowed_at','due_at','status','processed_by'];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function returns()
    {
        return $this->hasMany(ReturnTransaction::class);
    }
}
