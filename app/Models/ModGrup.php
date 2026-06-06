<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModGrup extends Model
{
    protected $table = 'mod_grup';
    public $incrementing = false;
    public $timestamps = false;

    // PK compuesta — Eloquent no la soporta nativamente,
    // se declara para documentación pero las búsquedas
    // deben hacerse con where doble
    protected $primaryKey = null;

    protected $fillable = [
        'grup_cod',
        'mod_cod',
        'mod_grup_fins',
        'mod_grup_create',
        'mod_grup_update',
    ];

    protected $casts = [
        'grup_cod'        => 'integer',
        'mod_cod'         => 'integer',
        'mod_grup_fins'   => 'date',
        'mod_grup_create' => 'datetime',
        'mod_grup_update' => 'datetime',
    ];

    // Relación con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grup_cod', 'grup_cod');
    }

    // Relación con Modulo
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'mod_cod', 'mod_cod');
    }
}