<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname','lastname','middlename','gender','purok','address','contact','organization','status'
    ];

    public function lendings()
    {
        return $this->hasMany(LendingTransaction::class);
    }
}
