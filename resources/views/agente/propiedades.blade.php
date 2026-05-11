@extends('layouts.panel')
@section('titulo','Mis propiedades')
@section('titulo_pagina','Mis propiedades')

@section('contenido')
<div class="card">
    <div class="card-header">
        <span class="card-title">Lista de mis propiedades</span>
        <button class="btn-primary" onclick="abrirModal()">+ Registrar propiedad</button>
    </div>
<div class="w-full overflow-x-auto shadow-sm rounded-lg border border-gray-200">
<table class="min-w-[600px] w-full text-sm text-left">
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
                        class="btn-edit btn-editar-propiedad w-full sm:w-auto"

                        data-id="{{ $p->id }}"
                        data-titulo="{{ $p->titulo }}"
                        data-tipo="{{ $p->tipo }}"
                        data-zona="{{ $p->zona }}"
                        data-precio="{{ $p->precio }}"
                        data-area="{{ $p->area }}"
                        data-descripcion="{{ $p->descripcion }}"
                        data-estado="{{ $p->estado }}"
                        data-imagen="{{ $p->imagen }}">

                        Editar

                    </button>
                    <form method="POST"
      action="{{ route('admin.propiedades.destroy', $p) }}"
      class="form-eliminar"
      data-title="{{ $p->titulo }}">

    @csrf
    @method('DELETE')

    <button type="button"
            class="btn-delete open-delete-modal w-full sm:w-auto"
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

</div>

@if($errors->any())
<div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px">
    <strong>Errores de validación:</strong>
    <ul style="margin:6px 0 0 18px;padding:0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="modal-overlay" id="modalOverlay">
<div class="modal w-[95%] max-w-lg mx-auto sm:w-full">
    <h2 id="modalTitulo">Registrar propiedad</h2>
    <form id="formPropiedad" method="POST" action="{{ route('agente.propiedades.store') }}" enctype="multipart/form-data">
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
            <div class="form-group full"><label>Imagen <small style="color:#6c757d;font-weight:400">(dejar vacío para mantener la actual)</small></label><img id="aImgActual" src="" alt="Foto actual" style="display:none;max-height:100px;border-radius:6px;object-fit:cover;margin-bottom:6px;display:block"><input type="file" name="imagen" id="aImagen" accept="image/jpeg,image/png,image/jpg,image/webp"><br><img id="aPreview" src="" alt="Vista previa" style="display:none;margin-top:8px;max-height:100px;border-radius:6px;object-fit:cover"></div>
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
function editarPropiedad(id,titulo,tipo,zona,precio,area,descripcion,estado,imagen){
    form.action=`/agente/propiedades/${id}`;
    document.getElementById('methodField').innerHTML='<input type="hidden" name="_method" value="PUT">';
    document.getElementById('propTitulo').value=titulo; document.getElementById('propTipo').value=tipo;
    document.getElementById('propZona').value=zona; document.getElementById('propPrecio').value=precio;
    document.getElementById('propArea').value=area; document.getElementById('propDescripcion').value=descripcion;
    document.getElementById('propEstado').value=estado;
    const imgActual = document.getElementById('aImgActual');
    if (imagen) { imgActual.src='/storage/'+imagen; imgActual.style.display='block'; }
    else { imgActual.src=''; imgActual.style.display='none'; }
    document.getElementById('aPreview').style.display='none';
    document.getElementById('aImagen').value='';
    document.getElementById('modalTitulo').textContent='Editar propiedad'; document.getElementById('btnSubmit').textContent='Guardar cambios';
    overlay.classList.add('open');
}






document.getElementById('aImagen').addEventListener('change', function(){
    const prev = document.getElementById('aPreview');
    if (this.files && this.files[0]) {
        prev.src = URL.createObjectURL(this.files[0]);
        prev.style.display = 'block';
    }
});

document.querySelectorAll('.btn-editar-propiedad').forEach(btn => {
    btn.addEventListener('click', function(){
        editarPropiedad(
            this.dataset.id,
            this.dataset.titulo,
            this.dataset.tipo,
            this.dataset.zona,
            this.dataset.precio,
            this.dataset.area,
            this.dataset.descripcion,
            this.dataset.estado,
            this.dataset.imagen
        );
    });
});
</script>
@endpush
