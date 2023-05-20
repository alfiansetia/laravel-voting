<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'vote';

    protected $fillable = [
        'event_id',
        'calon_id',
    ];

    public function event()
    {
        $this->belongsTo(Event::class);
    }

    public function calon()
    {
        $this->belongsTo(Calon::class);
    }
}
