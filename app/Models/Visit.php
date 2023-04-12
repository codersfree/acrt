<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'country', 
        'link_id'
    ];

    //Relacion uno a muchos inversa
    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
