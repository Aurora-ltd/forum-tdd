<?php

namespace App\Models;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Channel extends Model
{
    use HasFactory;
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
    
}
