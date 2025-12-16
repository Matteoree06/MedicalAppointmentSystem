@extends('layouts.app')

@section('title', 'Detalle de Especialidad')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/especialidades') }}">Especialidades</a></li>
    <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
<div class="row" id="especialidadDetailContainer">
    <div class="col-12">
        <div class="text-center">
            <div class="spinner-border text-danger" role="status">
                <span class="visually-hidden">Cargando información de la especialidad...</span>
            </div>
            <p class="mt-2">Cargando información de la especialidad...</p>
        </div>
    </div>
</div>

<!-- Modal para JSON-LD -->
<div class="modal fade" id="especialidadJsonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-code me-2"></i>JSON-LD de la Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalJsonContent">
                    <div class="text-center">
                        <div class="spinner-border text-danger" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a id="modalApiLink" href="#" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i> Ver API Completa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cargar detalle de la especialidad
document.addEventListener('DOMContentLoaded', function() {
    loadEspecialidadDetail({{ $id }});
});

async function loadEspecialidadDetail(especialidadId) {
    try {
        const response = await fetch(`/api/jsonld/especialidades/${especialidadId}`);
        const especialidad = await response.json();
        
        const container = document.getElementById('especialidadDetailContainer');
        
        // Contar médicos
        const medicosCount = especialidad.hasPhysician ? especialidad.hasPhysician.length : 0;
        
        let html = `
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                            <i class="fas fa-heartbeat fa-3x"></i>
                        </div>
                        <h3 class="card-title">${especialidad.name || 'Especialidad Médica'}</h3>
                        
                        <div class="mt-3">
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-user-md me-1"></i>
                                ${medicosCount} médico(s)
                            </span>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-outline-danger" onclick="showEspecialidadJson('${especialidad.identifier}', '${especialidad.name}')">
                                <i class="fas fa-code me-1"></i> Ver JSON-LD
                            </button>
                            <a href="/api/jsonld/especialidades/${especialidad.identifier}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-external-link-alt me-1"></i> API Directa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-info-circle text-danger me-2"></i>
                            Información de la Especialidad
                        </h4>
                        
                        <div class="mb-4">
                            <h6><i class="fas fa-file-alt me-2 text-muted"></i> Descripción</h6>
                            <p class="card-text">${especialidad.description || 'No hay descripción disponible'}</p>
                        </div>
                        
                        <!-- Lista de médicos de esta especialidad -->
                        <div class="mt-4">
                            <h5>
                                <i class="fas fa-user-md me-2"></i>
                                Médicos Especializados
                                <span class="badge bg-primary">${medicosCount}</span>
                            </h5>
                            
                            <div id="medicosContainer" class="mt-3">
                                ${medicosCount > 0 ? `
                                    <div class="list-group">
                                        ${especialidad.hasPhysician.map(medico => `
                                            <a href="/medicos/${medico.identifier}" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">${medico.name || 'Médico'}</h6>
                                                    <small>ID: ${medico.identifier}</small>
                                                </div>
                                                <p class="mb-1">Haz clic para ver detalles del médico</p>
                                            </a>
                                        `).join('')}
                                    </div>
                                ` : `
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No hay médicos asignados a esta especialidad.
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
        
    } catch (error) {
        console.error('Error al cargar especialidad:', error);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar información de la especialidad: ${error.message}
                </div>
            </div>
        `;
    }
}

function showEspecialidadJson(especialidadId, especialidadName) {
    const modal = new bootstrap.Modal(document.getElementById('especialidadJsonModal'));
    
    document.querySelector('#especialidadJsonModal .modal-title').innerHTML = 
        `<i class="fas fa-code me-2"></i>JSON-LD: ${especialidadName}`;
    
    document.getElementById('modalApiLink').href = `/api/jsonld/especialidades/${especialidadId}`;
    
    modal.show();
    
    fetch(`/api/jsonld/especialidades/${especialidadId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalJsonContent').innerHTML = 
                '<pre><code>' + JSON.stringify(data, null, 2) + '</code></pre>';
        })
        .catch(error => {
            document.getElementById('modalJsonContent').innerHTML = 
                '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        });
}
</script>
@endpush