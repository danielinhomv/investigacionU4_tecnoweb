<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'grup_cod';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'grup_cod',
        'grup_name',
        'grup_descrip',
        'grup_est',
        'grup_create',
        'grup_update',
    ];

    protected $casts = [
        'grup_est'    => 'boolean',
        'grup_create' => 'datetime',
        'grup_update' => 'datetime',
    ];

    // Relación con Permiso
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'grup_cod', 'grup_cod');
    }

    // Relación con Persona a través de Permiso
    public function personas()
    {
        return $this->belongsToMany(
            Persona::class,
            'permiso',
            'grup_cod',
            'per_cod'
        )->withPivot('perm_pass', 'perm_fini', 'perm_ffin', 'perm_est', 'perm_create', 'perm_update');
    }

    // Relación con ModGrup
    public function modulos()
    {
        return $this->hasMany(ModGrup::class, 'grup_cod', 'grup_cod');
    }
}