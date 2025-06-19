<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrian';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'gender',
        'no_antrian',
        'urutan',
        'poli',
        'doctor_id',
        'tanggal',
        'status',
        'jam_antrian',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_antrian' => 'datetime:H:i',
    ];

    protected $attributes = [
        'status' => 'menunggu',
    ];

    // ============================================================================
    // RELATIONSHIPS
    // ============================================================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    // ============================================================================
    // STATIC METHODS - UPDATED FORMAT
    // ============================================================================

    public static function generateNoAntrian($poli, $tanggal)
    {
        // Tentukan prefix berdasarkan poli
        $prefix = match($poli) {
            'Umum' => 'UMUM',
            'Kebidanan' => 'BIDAN',
            default => strtoupper($poli)
        };
        
        // Cari nomor antrian terakhir untuk poli dan tanggal yang sama
        $lastAntrian = self::where('poli', $poli)
                          ->where('tanggal', $tanggal)
                          ->orderBy('urutan', 'desc')
                          ->first();
        
        // Generate nomor urut
        if ($lastAntrian) {
            $newNumber = $lastAntrian->urutan + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format: UMUM-1, BIDAN-2, dst
        return $prefix . '-' . $newNumber;
    }

    public static function generateUrutan($poli, $tanggal)
    {
        // Cari urutan terakhir untuk poli dan tanggal yang sama
        $lastUrutan = self::where('poli', $poli)
                         ->where('tanggal', $tanggal)
                         ->max('urutan');
        
        return $lastUrutan ? $lastUrutan + 1 : 1;
    }

    // ============================================================================
    // HELPER METHODS
    // ============================================================================

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'menunggu' => 'warning',
            'dipanggil' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
            default => 'secondary'
        };
    }

    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal->format('d/m/Y');
    }

    /**
     * Check if antrian can be edited
     */
    public function canEdit()
    {
        // Bisa edit jika:
        // 1. Status masih menunggu
        // 2. Tanggal antrian >= hari ini
        return $this->status === 'menunggu' && $this->tanggal >= today();
    }

    /**
     * Check if antrian can be cancelled
     */
    public function canCancel()
    {
        // Bisa cancel jika:
        // 1. Status masih menunggu
        // 2. Tanggal antrian >= hari ini
        return $this->status === 'menunggu' && $this->tanggal >= today();
    }

    /**
     * Check if antrian can be printed
     */
    public function canPrint()
    {
        // Bisa print jika:
        // 1. Status BUKAN dibatalkan
        // 2. Antrian masih ada (tidak dihapus)
        return $this->status !== 'dibatalkan';
    }

    /**
     * Get poli name for display
     */
    public function getPoliDisplayAttribute()
    {
        return match($this->poli) {
            'Umum' => 'Poli Umum',
            'Kebidanan' => 'Poli Kebidanan',
            default => 'Poli ' . $this->poli
        };
    }

    /**
     * Get queue prefix based on poli
     */
    public function getQueuePrefixAttribute()
    {
        return match($this->poli) {
            'Umum' => 'UMUM',
            'Kebidanan' => 'BIDAN',
            default => strtoupper($this->poli)
        };
    }

    // ============================================================================
    // SCOPES
    // ============================================================================

    /**
     * Scope for today's queues
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    /**
     * Scope for active queues (menunggu, dipanggil)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['menunggu', 'dipanggil']);
    }

    /**
     * Scope for specific poli
     */
    public function scopeByPoli($query, $poli)
    {
        return $query->where('poli', $poli);
    }

    /**
     * Scope for specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}