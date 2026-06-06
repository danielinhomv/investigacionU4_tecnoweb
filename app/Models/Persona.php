<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'per_cod';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'per_cod',
        'per_nom',
        'per_appm',
        'per_prof',
        'per_telf',
        'per_cel',
        'per_email',
        'per_dir',
        'per_fnac',
        'per_lnac',
        'per_est',
        'per_create',
        'per_update',
        'per_foto',
    ];

    protected $casts = [
        'per_fnac'   => 'date',
        'per_est'    => 'boolean',
        'per_create' => 'datetime',
        'per_update' => 'datetime',
    ];

    protected $attributes = [
        'per_foto' => 'img/placeholder.png',
        'per_est'  => true,
    ];

    // Relación con Permiso
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'per_cod', 'per_cod');
    }

    // Nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return trim($this->per_nom) . ' ' . trim($this->per_appm);
    }
}