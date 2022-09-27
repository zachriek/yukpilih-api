<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $with = ['user'];

    protected $hidden = [];
    protected $fillable = [
        'choice_id',
        'user_id',
        'poll_id',
        'division_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
