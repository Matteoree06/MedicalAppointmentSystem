@extends('layouts.app')

@section('content')
@php
  // Asegura que exista $id aunque cambies la ruta luego
  $citaId = $id ?? request()->route('id');
@endphp

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="m-0">Detalle Cita #{{ $citaId }}</h1>
    <a class="btn btn-outline-secondary" href="/citas">Volver</a>
  </div>

  <div id="alert" class="alert d-none" role="alert"></div>

  <div class="card">
    <div class="card-body" id="box">
      Cargando...
    </div>
  </div>
</div>

<script>
const CITA_ID = @json($citaId);

function showAlert(type, message) {
  const alertBox = document.getElementById('alert');
  alertBox.className = `alert alert-${type}`;
  alertBox.textContent = message;
  alertBox.classList.remove('d-none');
}

function splitFechaHora(fechaHora) {
  if (!fechaHora || typeof fechaHora !== 'string') return { fecha: '', hora: '' };
  const parts = fechaHora.split(' ');
  const fecha = parts[0] ?? '';
  const hora = (parts[1] ?? '').slice(0, 5);
  return { fecha, hora };
}

function normalizeOne(payload) {
  // Caso 1: objeto normal (tu /api/citas/{id})
  if (payload && typeof payload === 'object' && !payload.data && !payload.item) return payload;

  // Caso 2: { data: {...} }
  if (payload && payload.data) return payload.data;

  // Caso 3: JSON-LD (el item ya viene como objeto)
  if (payload && payload.item) return payload.item;

  return payload;
}

(async function () {
  const box = document.getElementById('box');

  try {
    const res = await fetch(`/api/citas/${CITA_ID}?ts=${Date.now()}`, {
      headers: { 'Accept': 'application/json' },
      cache: 'no-store'
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);

    const raw = await res.json();
    const c = normalizeOne(raw);

    const id = c.id ?? c.identifier ?? CITA_ID;
    const estado = c.estado ?? c.status ?? '';
    const motivo = c.motivo ?? c.description ?? '';
    const obs = c.observaciones ?? c.observacion ?? '';
    const fh = c.fecha_hora ?? c.appointmentTime ?? '';

    const { fecha, hora } = splitFechaHora(fh);

    const pacienteNombre = c.paciente?.nombre ?? c.patient?.name ?? '';
    const medicoNombre = c.medico?.nombre ?? c.doctor?.name ?? '';
    const consultorioNumero = c.consultorio?.numero ?? '';
    const consultorioUbic = c.consultorio?.ubicacion ?? '';
    const consultorioTxt = (consultorioNumero || consultorioUbic)
      ? `${consultorioNumero}${consultorioUbic ? ' (' + consultorioUbic + ')' : ''}`
      : (c.location?.name ?? '');

    box.innerHTML = `
      <div class="row g-3">
        <div class="col-md-6"><b>ID:</b> ${id}</div>
        <div class="col-md-6"><b>Estado:</b> ${estado}</div>

        <div class="col-md-6"><b>Fecha:</b> ${fecha}</div>
        <div class="col-md-6"><b>Hora:</b> ${hora}</div>

        <div class="col-12"><b>Motivo:</b> ${motivo}</div>
        <div class="col-12"><b>Observaciones:</b> ${obs || '-'}</div>

        <hr class="my-2">

        <div class="col-md-4"><b>Paciente:</b> ${pacienteNombre || '-'}</div>
        <div class="col-md-4"><b>Médico:</b> ${medicoNombre || '-'}</div>
        <div class="col-md-4"><b>Consultorio:</b> ${consultorioTxt || '-'}</div>
      </div>
    `;

  } catch (e) {
    box.innerHTML = 'Error cargando detalle';
    showAlert('danger', `No se pudo cargar /api/citas/${CITA_ID}: ${e.message}`);
  }
})();
</script>
@endsection
