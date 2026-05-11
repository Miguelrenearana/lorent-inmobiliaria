<?php
// app/Http/Controllers/SolicitudController.php
namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudVisita;
use App\Models\Propiedad;
use App\Models\RegistroActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    public function misSolicitudes()
    {
        $solicitudes = SolicitudVisita::with('propiedad')
            ->where('cliente_id', Auth::id())
            ->orderBy('id','desc')
            ->get();

        return view('cliente.solicitudes', compact('solicitudes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'propiedad_id'    => 'required|exists:propiedades,id',
            'fecha_solicitada'=> 'required|date|after_or_equal:today',
            'mensaje'         => 'required|min:5',
        ]);

        SolicitudVisita::create([
            'propiedad_id'    => $request->propiedad_id,
            'cliente_id'      => Auth::id(),
            'fecha_solicitada'=> $request->fecha_solicitada,
            'mensaje'         => $request->mensaje,
            'estado'          => 'Pendiente',
        ]);

        $prop = Propiedad::find($request->propiedad_id);
        RegistroActividad::log('Solicitud de visita enviada',
            "Cliente solicitó visita para \"{$prop->titulo}\" el {$request->fecha_solicitada}.");

        return redirect()->route('cliente.propiedades')
                         ->with('success','Solicitud enviada. Un agente te contactará pronto.');
    }

    public function visitasAgente(Request $request)
    {
        $filtro  = $request->query('estado','todas');
        $estados = ['Pendiente','Confirmada','Cancelada'];

        $query = SolicitudVisita::with(['propiedad','cliente'])
            ->whereHas('propiedad', fn($q) => $q->where('agente_id', Auth::id()));

        if (in_array($filtro, $estados)) $query->where('estado', $filtro);

        $solicitudes = $query->orderBy('fecha_solicitada')->get();
        return view('agente.visitas', compact('solicitudes','filtro'));
    }

    public function actualizarEstado(Request $request, SolicitudVisita $solicitud)
    {
        $request->validate(['estado' => 'required|in:Aceptada,Rechazada']);
        $solicitud->update(['estado' => $request->estado]);
        $msg = $request->estado === 'Aceptada' ? 'Visita confirmada.' : 'Visita cancelada.';
        return back()->with('success', $msg);
    }

    public function visitasAsistente()
    {
        $solicitudes = SolicitudVisita::with(['propiedad','cliente'])
            ->orderBy('fecha_solicitada')
            ->get();
        return view('asistente.visitas', compact('solicitudes'));
    }

    public function clientesAgente()
    {
        $clientes = SolicitudVisita::with('cliente')
            ->whereHas('propiedad', fn($q) => $q->where('agente_id', Auth::id()))
            ->select('cliente_id')
            ->selectRaw('COUNT(*) as total_visitas')
            ->selectRaw('MAX(fecha_solicitada) as ultima_visita')
            ->groupBy('cliente_id')
            ->get();

        return view('agente.clientes', compact('clientes'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $solicitud = SolicitudVisita::findOrFail($id);
        $solicitud->estado = $request->estado;
        $solicitud->save();
        return back()->with('success', 'Estado actualizado correctamente');
    }

    public function cancelar(Request $request, $id)
    {
        $solicitud = SolicitudVisita::where('id', $id)
            ->where('cliente_id', Auth::id())
            ->where('estado', 'Pendiente')
            ->firstOrFail();

        $solicitud->update(['estado' => 'Rechazada']);
        RegistroActividad::log('Visita cancelada', 'El cliente canceló la solicitud #'.$id);

        return back()->with('success', 'Solicitud cancelada.');
    }
}