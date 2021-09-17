<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'category_id', 'duration', 'excluded', 'group_size', 'ticket', 'images', 'included', 'overview', 'price', 'discount', 'min_booking_amount', 'return_date', 'start_date', 'tour_plan', 'vehicle', 'status'];

    public function scopeNotStart($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    public function scopeName($query, $keyword)
    {
        if (!empty($keyword)) {
            return $query->where('name', 'LIKE', "%$keyword%");
        } else {
            return $query;
        }
    }

    public function scopeOverview($query, $keyword)
    {
        if (!empty($keyword)) {
            return $query->orWhere('overview', 'LIKE', "%$keyword%");
        } else {
            return $query;
        }
    }

    public function scopeCategory($query, $category)
    {
        if (!empty($category)) {
            return $query->whereIn('category_id', $category);
        } else {
            return $query;
        }
    }

    public function scopeDuration($query, $duration)
    {
        if (!empty($duration)) {
            return $query->whereIn('duration_day', $duration);
        } else {
            return $query;
        }
    }

    public function scopeMin_price($query, $price)
    {
        if (!empty($price)) {
            return $query->where('price', '>=', $price);
        } else {
            return $query;
        }
    }

    public function scopeMax_price($query, $price)
    {
        if (!empty($price)) {
            return $query->where('price', '<=', $price);
        } else {
            return $query;
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
