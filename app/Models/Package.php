<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'category_id', 'duration', 'excluded', 'group_size', 'images', 'included', 'overview', 'price', 'discount', 'return_date', 'start_date', 'tour_plan', 'vehicle'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
