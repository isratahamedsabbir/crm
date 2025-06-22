<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
