<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    // ============================================================================
    // PRIMARY KEY CONFIGURATION
    // ============================================================================
    
    /**
     * Primary key bukan auto-increment (menggunakan string: D001, D002, dst)
     */
    public $incrementing = false;
    protected $keyType = 'string';

    // ============================================================================
    // FILLABLE ATTRIBUTES
    // ============================================================================
    
    protected $fillable = [
        'id',                // D001, D002, dst (primary key)
        'nama',              // Nama dokter
        'spesialisasi',      // Spesialisasi dokter
        'hari',              // Hari praktek (dari migration tambahan)
        'mulai_praktek',     // Jam mulai praktek
        'selesai_praktek',   // Jam selesai praktek
        'foto'               // Foto dokter (nullable)
    ];

    // ============================================================================
    // CASTS
    // ============================================================================
    
    protected $casts = [
       
    ];

    // ============================================================================
    // RELATIONSHIPS
    // ============================================================================

    /**
     * Relationship dengan Antrian
     * Satu doctor bisa punya banyak antrian
     */
    public function antrians()
    {
        return $this->hasMany(Antrian::class, 'dokter_id', 'id');
    }

    // ============================================================================
    // HELPER METHODS
    // ============================================================================

    /**
     * Cek apakah dokter sedang praktek pada waktu tertentu
     */
    public function isPraktek($waktu = null)
    {
        if (!$waktu) {
            $waktu = now()->format('H:i');
        }
        
        return $waktu >= $this->mulai_praktek->format('H:i') && 
               $waktu <= $this->selesai_praktek->format('H:i');
    }

    /**
     * Get formatted jam praktek untuk display
     */
    public function getJamPraktekAttribute()
    {
        return $this->mulai_praktek->format('H:i') . ' - ' . $this->selesai_praktek->format('H:i');
    }

    /**
     * Get nama lengkap dengan gelar
     */
    public function getNamaLengkapAttribute()
    {
        return $this->nama;
    }

    /**
     * Auto generate ID dokter
     */
    public static function generateId()
    {
        $lastDoctor = self::orderBy('id', 'desc')->first();
        
        if ($lastDoctor) {
            $lastNumber = (int) substr($lastDoctor->id, 1); // Ambil angka setelah 'D'
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'D' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        // Hasil: D001, D002, D003, dst
    }

    // ============================================================================
    // SCOPES
    // ============================================================================

    /**
     * Scope untuk dokter berdasarkan spesialisasi
     */
    public function scopeBySpesialisasi($query, $spesialisasi)
    {
        return $query->where('spesialisasi', $spesialisasi);
    }

    /**
     * Scope untuk dokter yang praktek hari ini
     */
    public function scopePraktekHariIni($query)
    {
        $hari = now()->locale('id')->dayName; // Senin, Selasa, dst
        return $query->where('hari', 'like', "%{$hari}%");
    }

    /**
     * Scope untuk dokter yang sedang praktek sekarang
     */
    public function scopeSedangPraktek($query)
    {
        $now = now()->format('H:i:s');
        return $query->where('mulai_praktek', '<=', $now)
                    ->where('selesai_praktek', '>=', $now);
    }

    // ============================================================================
    // COMPATIBILITY METHODS
    // ============================================================================
    
    /**
     * Compatibility dengan view yang menggunakan 'name'
     */
    public function getNameAttribute()
    {
        return $this->nama;
    }

    /**
     * Compatibility dengan view yang menggunakan 'specialization'
     */
    public function getSpecializationAttribute()
    {
        return $this->spesialisasi;
    }
}