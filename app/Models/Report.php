<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['type','generated_by','generated_at','total_records','meta'];

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
