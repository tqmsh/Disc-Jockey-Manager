<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = ['user_id', 'credits_given', 'payment_amount', 'date'];


    public function vendor() {
        return $this->belongsTo(Vendors::class, 'user_id');
    }
}
