@extends('layouts.panel')
@section('titulo','Mis propiedades')
@section('titulo_pagina','Mis propiedades')

@section('contenido')
<div class="card">
    <div class="card-header">
        <span class="card-title">Lista de mis propiedades</span>
        <button class="btn-primary" onclick="abrirModal()">+ Registrar propiedad</button>
    </div>
    <table>
        <thead><tr><th>#</th><th>Título</th><th>Tipo</th><th>Zona</th><th>Precio</th><th>Área</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($propiedades as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->titulo }}</td>
            <td>{{ $p->tipo }}</td>
            <td>{{ $p->zona }}</td>
            <td>${{ number_format($p->precio,0,',','.') }}</td>
            <td>{{ $p->area ? $p->area.' m²' : '—' }}</td>
            <td><span class="badge badge-{{ strtolower($p->estado) }}">{{ $p->estado }}</span></td>
            <td>
                <div class="action-btns">
                    <button
                        type="button"
                        class="btn-edit btn-editar-propiedad"

                        data-id="{{ $p->id }}"
                        data-titulo="{{ $p->titulo }}"
                        data-tipo="{{ $p->tipo }}"
                        data-zona="{{ $p->zona }}"
                        data-precio="{{ $p->precio }}"
                        data-area="{{ $p->area }}"
                        data-descripcion="{{ $p->descripcion }}"
                        data-estado="{{ $p->estado }}">

                        Editar

                    </button>
                    <form method="POST"
      action="{{ route('admin.propiedades.destroy', $p) }}"
      class="form-eliminar"
      data-title="{{ $p->titulo }}">

    @csrf
    @method('DELETE')

    <button type="button"
            class="btn-delete open-delete-modal"
            data-name="{{ $p->titulo }}">
        Eliminar
    </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;color:#6c757d;padding:20px">No tienes propiedades registradas.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="modalOverlay">
<div class="modal">
    <h2 id="modalTitulo">Registrar propiedad</h2>
    <form id="formPropiedad" method="POST" action="{{ route('agente.propiedades.store') }}">
        @csrf <span id="methodField"></span>
        <div class="form-grid">
            <div class="form-group full"><label>Título</label><input type="text" name="titulo" id="propTitulo" required></div>
            <div class="form-group"><label>Tipo</label>
                <select name="tipo" id="propTipo">
                    <option value="Venta">Venta</option><option value="Alquiler">Alquiler</option><option value="Anticretico">Anticretico</option>
                </select>
            </div>
            <div class="form-group"><label>Zona</label><input type="text" name="zona" id="propZona" required></div>
            <div class="form-group"><label>Precio ($)</label><input type="number" name="precio" id="propPrecio" step="0.01" min="0" required></div>
            <div class="form-group"><label>Área (m²)</label><input type="number" name="area" id="propArea" step="0.01" min="0" required></div>
            <div class="form-group"><label>Estado</label>
                <select name="estado" id="propEstado">
                    <option value="Disponible">Disponible</option><option value="Reservado">Reservado</option><option value="Vendido">Vendido</option>
                </select>
            </div>
            <div class="form-group full"><label>Descripción</label><textarea name="descripcion" id="propDescripcion" rows="3" required></textarea></div>
        </div>
        <div class="form-actions">
            <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
            <button type="submit" class="btn-primary" id="btnSubmit">Registrar</button>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
const overlay=document.getElementById('modalOverlay');
const form=document.getElementById('formPropiedad');
function abrirModal(){ form.reset(); form.action='{{ route("agente.propiedades.store") }}'; document.getElementById('methodField').innerHTML=''; document.getElementById('modalTitulo').textContent='Registrar propiedad'; document.getElementById('btnSubmit').textContent='Registrar'; overlay.classList.add('open'); }
function cerrarModal(){ overlay.classList.remove('open'); }
overlay.addEventListener('click',e=>{ if(e.target===overlay) cerrarModal(); });
function editarPropiedad(id,titulo,tipo,zona,precio,area,descripcion,estado){
    form.action=`/agente/propiedades/${id}`;
    document.getElementById('methodField').innerHTML='<input type="hidden" name="_method" value="PUT">';
    document.getElementById('propTitulo').value=titulo; document.getElementById('propTipo').value=tipo;
    document.getElementById('propZona').value=zona; document.getElementById('propPrecio').value=precio;
    document.getElementById('propArea').value=area; document.getElementById('propDescripcion').value=descripcion;
    document.getElementById('propEstado').value=estado;
    document.getElementById('modalTitulo').textContent='Editar propiedad'; document.getElementById('btnSubmit').textContent='Guardar cambios';
    overlay.classList.add('open');
}





</script>
@endpush
