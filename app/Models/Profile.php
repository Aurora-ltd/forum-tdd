<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'home_address',
        'office_address',
        'phone',
        'mobile',
    ];

    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
