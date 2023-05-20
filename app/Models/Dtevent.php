<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dtevent extends Model
{
    use HasFactory;

    protected $table = 'dtevent';

    protected $fillable = [
        'event_id',
        'calon_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function calon()
    {
        return $this->belongsTo(Calon::class);
    }
}
