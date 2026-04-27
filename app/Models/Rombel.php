<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    protected $fillable = ['nama_rombel'];
    
    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }
}
