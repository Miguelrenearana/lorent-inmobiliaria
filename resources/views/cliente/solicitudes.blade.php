@extends('layouts.panel')
@section('titulo','Mis solicitudes')
@section('titulo_pagina','Mis solicitudes de visita')

@push('styles')
<style>
table{width:100%;border-collapse:collapse;font-size:13px}
th{text-align:left;color:#6c757d;font-weight:500;padding:10px 14px;border-bottom:2px solid #e2e6ea;background:#f8f9fa;white-space:nowrap}
td{padding:11px 14px;border-bottom:1px solid #f0f2f5;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#f8f9fa}
</style>
@endpush

@section('contenido')
<div class="card">
    <div class="card-header">
        <span class="card-title">Historial de solicitudes</span>
        <a href="{{ route('cliente.propiedades') }}" class="btn-primary">+ Nueva solicitud</a>
    </div>
<div class="w-full overflow-x-auto shadow-sm rounded-lg border border-gray-200">
<table class="min-w-[600px] w-full text-sm text-left">
        <thead>
            <tr><th>Propiedad</th><th>Zona</th><th>Tipo</th><th>Fecha solicitada</th><th>Mensaje</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        @forelse($solicitudes as $s)
        @php
            $cls = match($s->estado) {
                'Aceptada'  => 'badge-disponible',
                'Completada'=> 'badge-disponible',
                'Rechazada' => 'badge-vendido',
                'Pendiente' => 'badge-reservado',
                default     => 'badge-reservado'
            };
            $label = $s->estado === 'Rechazada' ? 'Cancelada' : $s->estado;
        @endphp
        <tr>
            <td>{{ $s->propiedad->titulo ?? '—' }}</td>
            <td>{{ $s->propiedad->zona ?? '—' }}</td>
            <td>{{ $s->propiedad->tipo ?? '—' }}</td>
            <td>{{ \Carbon\Carbon::parse($s->fecha_solicitada)->format('d/m/Y') }}</td>
            <td class=" max-w-[200px] break-words whitespace-normal" style="max-width:200px;color:#6c757d">{{ Str::limit($s->mensaje, 60) }}</td>
            <td><span class="badge {{ $cls }}">{{ $label }}</span></td>
            <td>
                @if($s->estado === 'Pendiente')
                <form method="POST" action="{{ route('cliente.solicitudes.cancelar', $s->id) }}" style="display:inline">
                    @csrf @method('PATCH')
                    <button
                        type="submit"
                        style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;transition:background .15s"
                        onmouseover="this.style.background='#fecaca'"
                        onmouseout="this.style.background='#fee2e2'"
                        onclick="return confirm('¿Estás seguro de cancelar esta solicitud? Esta acción no se puede deshacer.')"
                    >Cancelar</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;color:#6c757d;padding:30px">
                No tienes solicitudes aún.
                <a href="{{ route('cliente.propiedades') }}" style="color:#185FA5;margin-left:6px">Ver propiedades →</a>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

</div>
@endsection
