@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="m-0">Citas</h1>
    <button id="btnReload" class="btn btn-outline-primary">Recargar</button>
  </div>

  <div id="alert" class="alert d-none" role="alert"></div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr><td colspan="5">Cargando...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<script>
function normalizeList(payload) {
  // Caso 1: array normal (tu /api/citas actual)
  if (Array.isArray(payload)) return payload;

  // Caso 2: { data: [...] }
  if (payload && Array.isArray(payload.data)) return payload.data;

  // Caso 3: JSON-LD ItemList { itemListElement: [ { item: {...} } ] }
  if (payload && Array.isArray(payload.itemListElement)) {
    return payload.itemListElement.map(x => x.item ?? x).filter(Boolean);
  }

  return [];
}

function splitFechaHora(fechaHora) {
  if (!fechaHora || typeof fechaHora !== 'string') return { fecha: '', hora: '' };

  // "YYYY-MM-DD HH:MM:SS"
  const parts = fechaHora.split(' ');
  const fecha = parts[0] ?? '';
  const hora = (parts[1] ?? '').slice(0, 5); // HH:MM
  return { fecha, hora };
}

function showAlert(type, message) {
  const alertBox = document.getElementById('alert');
  alertBox.className = `alert alert-${type}`;
  alertBox.textContent = message;
  alertBox.classList.remove('d-none');
}

function hideAlert() {
  const alertBox = document.getElementById('alert');
  alertBox.classList.add('d-none');
  alertBox.textContent = '';
}

async function cargarCitas() {
  const tbody = document.getElementById('tbody');
  hideAlert();

  tbody.innerHTML = `<tr><td colspan="5">Cargando...</td></tr>`;

  try {
    // cache: 'no-store' para evitar “a veces sale vacío”
    const res = await fetch(`/api/citas?ts=${Date.now()}`, {
      headers: { 'Accept': 'application/json' },
      cache: 'no-store'
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);

    const raw = await res.json();
    const data = normalizeList(raw);

    if (!Array.isArray(data) || data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5">No hay citas registradas.</td></tr>`;
      return;
    }

    tbody.innerHTML = data.map(c => {
      const id = c.id ?? c.identifier ?? '';
      const estado = c.estado ?? c.status ?? '';
      const fh = c.fecha_hora ?? c.appointmentTime ?? '';
      const { fecha, hora } = splitFechaHora(fh);

      return `
        <tr>
          <td>${id}</td>
          <td>${fecha}</td>
          <td>${hora}</td>
          <td>${estado}</td>
          <td>
            <a class="btn btn-sm btn-primary" href="/citas/${id}">Ver</a>
          </td>
        </tr>
      `;
    }).join('');

  } catch (e) {
    tbody.innerHTML = `<tr><td colspan="5">Error cargando citas</td></tr>`;
    showAlert('danger', 'No se pudo cargar /api/citas: ' + e.message);
  }
}

document.getElementById('btnReload').addEventListener('click', cargarCitas);
cargarCitas();
</script>
@endsection
