<?php
// app/Models/Propiedad.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $table   = 'propiedades';
    public $timestamps = false;

    protected $fillable = [
        'titulo','tipo','zona','precio','area','descripcion','estado','agente_id','imagen',
    ];

    public function agente() {
        return $this->belongsTo(Usuario::class, 'agente_id');
    }
    public function solicitudes() {
        return $this->hasMany(SolicitudVisita::class, 'propiedad_id');
    }
}