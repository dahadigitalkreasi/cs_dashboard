<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IklanHarian extends Model
{
    protected $table = 'iklan_harian';

    protected $fillable = [
        'tanggal', 'cs', 'produk', 'lead_meta', 'lead_cs', 'spend_iklan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'lead_meta' => 'integer',
        'lead_cs' => 'integer',
        'spend_iklan' => 'decimal:2',
    ];
}
