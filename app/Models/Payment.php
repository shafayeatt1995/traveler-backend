<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['payment_type', 'payment_email', 'transaction_number', 'amount'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
