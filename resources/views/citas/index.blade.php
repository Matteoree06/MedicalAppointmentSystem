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
async function cargarCitas() {
  const tbody = document.getElementById('tbody');
  const alertBox = document.getElementById('alert');

  alertBox.classList.add('d-none');
  alertBox.textContent = '';
  tbody.innerHTML = `<tr><td colspan="5">Cargando...</td></tr>`;

  try {
    const res = await fetch('/api/citas', {
      headers: { 'Accept': 'application/json' },
      credentials: 'include'
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);

    const data = await res.json();

    if (!Array.isArray(data) || data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5">No hay citas registradas.</td></tr>`;
      return;
    }

    tbody.innerHTML = data.map(c => {
      const fh = c.fecha_hora ?? '';
      const partes = fh.split(' ');
      const fecha = partes[0] ?? '';
      const hora = (partes[1] ?? '').slice(0,5);

      return `
        <tr>
          <td>${c.id ?? ''}</td>
          <td>${fecha}</td>
          <td>${hora}</td>
          <td>${c.estado ?? ''}</td>
          <td><a class="btn btn-sm btn-primary" href="/citas/${c.id}">Ver</a></td>
        </tr>
      `;
    }).join('');

  } catch (e) {
    tbody.innerHTML = `<tr><td colspan="5">Error cargando citas</td></tr>`;
    alertBox.className = 'alert alert-danger';
    alertBox.textContent = 'No se pudo cargar /api/citas: ' + e.message;
    alertBox.classList.remove('d-none');
  }
}

document.getElementById('btnReload').addEventListener('click', cargarCitas);
cargarCitas();
</script>
@endsection
