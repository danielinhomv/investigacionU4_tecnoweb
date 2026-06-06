<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'accion';
    public $incrementing = false;
    public $timestamps = false;

    // PK compuesta (mod_cod, acc_cod)
    protected $primaryKey = null;

    protected $fillable = [
        'mod_cod',
        'acc_cod',
        'acc_name',
        'acc_param',
        'acc_descrip',
        'acc_est',
        'acc_create',
        'acc_update',
    ];

    protected $casts = [
        'mod_cod'    => 'integer',
        'acc_cod'    => 'decimal:2',
        'acc_est'    => 'boolean',
        'acc_create' => 'datetime',
        'acc_update' => 'datetime',
    ];

    // Relación con Modulo
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'mod_cod', 'mod_cod');
    }

    // Scope: solo acciones activas
    public function scopeActivas($query)
    {
        return $query->where('acc_est', true);
    }

    // Buscar por PK compuesta
    public static function findByPk(int $modCod, float $accCod)
    {
        return static::where('mod_cod', $modCod)
                     ->where('acc_cod', $accCod)
                     ->first();
    }
}