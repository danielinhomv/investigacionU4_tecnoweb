<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulo';
    protected $primaryKey = 'mod_cod';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'mod_name',
        'mod_descrip',
        'mod_file',
        'mod_nivel',
        'mod_est',
        'mod_create',
        'mod_update',
    ];

    protected $casts = [
        'mod_cod'    => 'integer',
        'mod_nivel'  => 'integer',
        'mod_est'    => 'boolean',
        'mod_create' => 'datetime',
        'mod_update' => 'datetime',
    ];

    // Relación con Accion
    public function acciones()
    {
        return $this->hasMany(Accion::class, 'mod_cod', 'mod_cod');
    }

    // Relación con ModGrup
    public function modGrups()
    {
        return $this->hasMany(ModGrup::class, 'mod_cod', 'mod_cod');
    }

    // Relación con Grupo a través de mod_grup
    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'mod_grup',
            'mod_cod',
            'grup_cod'
        )->withPivot('mod_grup_fins', 'mod_grup_create', 'mod_grup_update');
    }

    // Módulos activos
    public function scopeActivos($query)
    {
        return $query->where('mod_est', true);
    }

    // Módulos por nivel
    public function scopeNivel($query, int $nivel)
    {
        return $query->where('mod_nivel', $nivel);
    }
}