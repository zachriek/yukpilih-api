<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $with = ['choices'];

    protected $hidden = [];
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'created_by'
    ];

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
