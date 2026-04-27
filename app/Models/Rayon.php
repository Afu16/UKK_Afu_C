<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rayon extends Model
{
    protected $fillable = ['nama_rayon'];
    public function rombel()
    {
        return $this->hasMany(Rombel::class);
    }
    
}
