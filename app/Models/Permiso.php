<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = ['per_cod', 'grup_cod']; // PK compuesta
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'per_cod',
        'grup_cod',
        'perm_pass',
        'perm_fini',
        'perm_ffin',
        'perm_est',
        'perm_create',
        'perm_update',
    ];

    protected $casts = [
        'grup_cod'    => 'integer',
        'perm_fini'   => 'date',
        'perm_ffin'   => 'date',
        'perm_est'    => 'boolean',
        'perm_create' => 'datetime',
        'perm_update' => 'datetime',
    ];

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_cod', 'per_cod');
    }

    // Relación con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grup_cod', 'grup_cod');
    }
}