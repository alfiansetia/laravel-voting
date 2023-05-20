<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'event';
    protected $fillable = [
        'name',
        'date',
        'expired',
        'desc',
    ];

    public function dtevent()
    {
        return $this->hasMany(Dtevent::class);
    }

    public function calon()
    {
        return $this->belongsTo(Calon::class);
    }

    public function vote()
    {
        return $this->hasMany(Vote::class);
    }
}
