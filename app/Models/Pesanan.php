<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'id_pesanan',
        'tgl_chat_masuk',
        'tgl_order',
        'cs',
        'nama_pembeli',
        'no_hp',
        'alamat',
        'no_resi',
        'status',
        'ekspedisi',
        'pembayaran',
        'nominal_dp',
        'nominal_non_cod',
        'nominal_cod',
        'kategori',
        'qty',
        'notes',
        'harga',
        'hpp',
        'ongkir',
        'fee_cod',
        'iklan',
        'keuntungan',
        'catatan_internal',
    ];

    protected $casts = [
        'tgl_chat_masuk' => 'datetime',
        'tgl_order' => 'datetime',
        'qty' => 'integer',

        'nominal_dp' => 'decimal:2',
        'nominal_non_cod' => 'decimal:2',
        'nominal_cod' => 'decimal:2',

        'harga' => 'decimal:2',
        'hpp' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'fee_cod' => 'decimal:2',
        'iklan' => 'decimal:2',
        'keuntungan' => 'decimal:2',
    ];
}
