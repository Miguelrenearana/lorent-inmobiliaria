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
    <table>
        <thead>
            <tr><th>Propiedad</th><th>Zona</th><th>Tipo</th><th>Fecha solicitada</th><th>Mensaje</th><th>Estado</th></tr>
        </thead>
        <tbody>
        @forelse($solicitudes as $s)
        @php
            $cls = match($s->estado) {
                'confirmada' => 'badge-disponible',
                'cancelada'  => 'badge-vendido',
                default      => 'badge-reservado'
            };
        @endphp
        <tr>
            <td>{{ $s->propiedad->titulo ?? '—' }}</td>
            <td>{{ $s->propiedad->zona ?? '—' }}</td>
            <td>{{ $s->propiedad->tipo ?? '—' }}</td>
            <td>{{ \Carbon\Carbon::parse($s->fecha_solicitada)->format('d/m/Y') }}</td>
            <td style="max-width:200px;color:#6c757d">{{ Str::limit($s->mensaje, 60) }}</td>
            <td><span class="badge {{ $cls }}">{{ ucfirst($s->estado) }}</span></td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;color:#6c757d;padding:30px">
                No tienes solicitudes aún.
                <a href="{{ route('cliente.propiedades') }}" style="color:#185FA5;margin-left:6px">Ver propiedades →</a>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
