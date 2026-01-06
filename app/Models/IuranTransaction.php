<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranTransaction extends Model
{
    use HasFactory;

    protected $table = 'db_iuaran_transactions';

    protected $fillable = [
        'tanggal',
        'jam',
        'dari',
        'keterangan',
        'nominal',
        'bukti_foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function scopeFilterByTanggal($query, $dari = null, $sampai = null)
    {
        if ($dari) {
            $query->where('tanggal', '>=', $dari);
        }
        if ($sampai) {
            $query->where('tanggal', '<=', $sampai);
        }
        return $query;
    }

    public function scopeFilterByDari($query, $dari)
    {
        if ($dari) {
            $query->where('dari', 'like', '%' . $dari . '%');
        }
        return $query;
    }
}
