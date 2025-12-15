@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="m-0">Detalle Cita #{{ $id }}</h1>
    <a class="btn btn-outline-secondary" href="/citas">Volver</a>
  </div>

  <div id="alert" class="alert d-none" role="alert"></div>

  <div class="card">
    <div class="card-body" id="box">Cargando...</div>
  </div>
</div>

<script>
(async function () {
  const id = @json($id);
  const alertBox = document.getElementById('alert');
  const box = document.getElementById('box');

  try {
    const res = await fetch(`/api/citas/${id}`, {
      headers: { 'Accept': 'application/json' },
      credentials: 'include'
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);

    const c = await res.json();

    // fecha_hora => fecha + hora
    const fh = c.fecha_hora ?? '';
    const partes = fh.split(' ');
    const fecha = partes[0] ?? '';
    const hora = (partes[1] ?? '').slice(0,5);

    box.innerHTML = `
      <div class="row g-3">
        <div class="col-md-6"><b>ID:</b> ${c.id ?? ''}</div>
        <div class="col-md-6"><b>Estado:</b> ${c.estado ?? ''}</div>

        <div class="col-md-6"><b>Fecha:</b> ${fecha}</div>
        <div class="col-md-6"><b>Hora:</b> ${hora}</div>

        <div class="col-12"><b>Motivo:</b> ${c.motivo ?? ''}</div>
        <div class="col-12"><b>Observaciones:</b> ${c.observaciones ?? ''}</div>

        <hr class="my-2"/>

        <div class="col-md-4"><b>Paciente:</b> ${c.paciente?.nombre ?? ''}</div>
        <div class="col-md-4"><b>Médico:</b> ${c.medico?.nombre ?? ''}</div>
        <div class="col-md-4"><b>Consultorio:</b> ${c.consultorio?.numero ?? ''} (${c.consultorio?.ubicacion ?? ''})</div>
      </div>
    `;
  } catch (e) {
    box.innerHTML = 'Error cargando detalle';
    alertBox.className = 'alert alert-danger';
    alertBox.textContent = 'No se pudo cargar /api/citas/' + id + ': ' + e.message;
    alertBox.classList.remove('d-none');
  }
})();
</script>
@endsection
