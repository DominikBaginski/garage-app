<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Garage;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'garage_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
    
    public function getGarageNameAttribute()
    {
        return $this->garage->name;
    }

}
