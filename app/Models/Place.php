<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];
    
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
    
    public function runningPackages()
    {
        return $this->hasMany(Package::class)->whereDate('start_date', '>', now());
    }
}
