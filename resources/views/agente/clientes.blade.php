@extends('layouts.panel')
@section('titulo','Mis clientes')
@section('titulo_pagina','Mis clientes')

@section('contenido')
<div class="card">
    <div class="card-header">
        <span class="card-title">Clientes que solicitaron visitas <span style="font-size:12px;color:#6c757d;font-weight:400">({{ $clientes->count() }} clientes)</span></span>
    </div>
    <table>
        <thead><tr><th>Nombre</th><th>Correo</th><th>Total visitas</th><th>Última solicitud</th><th>Acción</th></tr></thead>
        <tbody>
        @forelse($clientes as $c)
        <tr>
            <td>{{ $c->cliente->nombre ?? '—' }}</td>
            <td>{{ $c->cliente->correo ?? '—' }}</td>
            <td style="text-align:center"><span class="badge badge-blue">{{ $c->total_visitas }}</span></td>
            <td>{{ $c->ultima_visita }}</td>
            <td><a href="{{ route('agente.visitas') }}" class="btn-edit">Ver visitas</a></td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:#6c757d;padding:20px">No tienes clientes aún.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
