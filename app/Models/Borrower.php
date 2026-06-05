<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'age',
        'purok',
        'address',
        'contact',
        'organization',
        'status',
    ];

    /**
     * Ang mga default na halaga para sa mga attributes ng modelo.
     * Siguradong 'active' ang simula ng bawat bagong borrower.
     */
    protected $attributes = [
        'status' => 'active',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
        ];
    }

    /**
     * Relationship sa LendingTransaction Model.
     * Hinahayaan ka nitong makuha ang kasaysayan (history) ng hiram ng borrower.
     */
    public function lendings()
    {
        return $this->hasMany(LendingTransaction::class, 'borrower_id');
    }
}
