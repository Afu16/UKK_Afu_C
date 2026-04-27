<?php

if (!function_exists('logActivity')) {
    function logActivity($aksi, $keterangan = '')
    {
        \App\Models\ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => $aksi,
            'keterangan' => $keterangan,
        ]);
    }
}