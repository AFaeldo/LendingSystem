<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_code','name','category_id','description','quantity','available','condition','status','image_path'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lendings()
    {
        return $this->hasMany(LendingTransaction::class);
    }
}
