<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Define the table name if it's not the default
    // protected $table = 'payments';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'payer_id', // Make sure this is the field used for the relationship
        'amount',
        'comments',
    ];

    // Define the relationship with the User model (payer)
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }
}
