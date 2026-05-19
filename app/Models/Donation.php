<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'amount',
        'currency',
        'name',
        'email',
        'message',
        'status',
        'wompi_transaction_id',
    ];
}
