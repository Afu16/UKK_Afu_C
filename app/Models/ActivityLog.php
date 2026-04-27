<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs'; // ganti ini

    protected $fillable = ['user_id', 'aksi', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}