@extends('layouts.app')

@section('title', 'Detalle del Consultorio')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/consultorios') }}">Consultorios</a></li>
    <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
<div class="row" id="consultorioDetailContainer">
    <div class="col-12">
        <div class="text-center">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Cargando información del consultorio...</span>
            </div>
            <p class="mt-2">Cargando información del consultorio...</p>
        </div>
    </div>
</div>

<!-- Modal para JSON-LD -->
<div class="modal fade" id="consultorioJsonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-code me-2"></i>JSON-LD del Consultorio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalJsonContent">
                    <div class="text-center">
                        <div class="spinner-border text-success" role="status"></div>
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
// Cargar detalle del consultorio
document.addEventListener('DOMContentLoaded', function() {
    loadConsultorioDetail({{ $id }});
});

async function loadConsultorioDetail(consultorioId) {
    try {
        const response = await fetch(`/api/jsonld/consultorios/${consultorioId}`);
        const consultorio = await response.json();
        
        const container = document.getElementById('consultorioDetailContainer');
        
        // Contar médicos
        const medicosCount = consultorio.medicalStaff ? consultorio.medicalStaff.length : 0;
        
        // Usar streetAddress como nombre si name es null
        const nombreConsultorio = consultorio.address?.streetAddress || consultorio.name || 'Consultorio';
        
        let html = `
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                            <i class="fas fa-hospital fa-3x"></i>
                        </div>
                        <h3 class="card-title">${nombreConsultorio}</h3>
                        
                        <div class="mt-3">
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-user-md me-1"></i>
                                ${medicosCount} médico(s)
                            </span>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-outline-success" onclick="showConsultorioJson('${consultorio.identifier}', '${nombreConsultorio}')">
                                <i class="fas fa-code me-1"></i> Ver JSON-LD
                            </button>
                            <a href="/api/jsonld/consultorios/${consultorio.identifier}" target="_blank" class="btn btn-outline-secondary">
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
                            <i class="fas fa-info-circle text-success me-2"></i>
                            Información del Consultorio
                        </h4>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6><i class="fas fa-hashtag me-2 text-muted"></i> Identificador</h6>
                                <p>${consultorio.identifier}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-map-marker-alt me-2 text-muted"></i> Nombre del Consultorio</h6>
                                <p>${consultorio.address?.streetAddress || consultorio.name || 'No especificado'}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6><i class="fas fa-location-dot me-2 text-muted"></i> Dirección</h6>
                            <p class="card-text">
                                ${consultorio.address?.streetAddress || 'Dirección no disponible'}
                            </p>
                        </div>
                        
                        <!-- Lista de médicos asignados -->
                        <div class="mt-4">
                            <h5>
                                <i class="fas fa-user-md me-2"></i>
                                Médicos Asignados
                                <span class="badge bg-primary">${medicosCount}</span>
                            </h5>
                            
                            <div id="medicosContainer" class="mt-3">
                                ${medicosCount > 0 ? `
                                    <div class="list-group">
                                        ${consultorio.medicalStaff.map(medico => `
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
                                        No hay médicos asignados a este consultorio.
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
        console.error('Error al cargar consultorio:', error);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar información del consultorio: ${error.message}
                </div>
            </div>
        `;
    }
}

function showConsultorioJson(consultorioId, consultorioName) {
    const modal = new bootstrap.Modal(document.getElementById('consultorioJsonModal'));
    
    document.querySelector('#consultorioJsonModal .modal-title').innerHTML = 
        `<i class="fas fa-code me-2"></i>JSON-LD: ${consultorioName}`;
    
    document.getElementById('modalApiLink').href = `/api/jsonld/consultorios/${consultorioId}`;
    
    modal.show();
    
    fetch(`/api/jsonld/consultorios/${consultorioId}`)
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