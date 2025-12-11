@extends('layouts.app')

@section('title', 'Médicos Disponibles')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Médicos</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-user-md text-primary me-2"></i>
        Médicos Disponibles
    </h1>
    
    <div>
        <button 
            id="toggleJsonLdBtn" 
            class="btn btn-outline-primary" 
            onclick="toggleJsonLd('toggleJsonLdBtn', 'jsonLdContainer'); loadJsonLd('/api/jsonld/medicos', 'jsonLdContent')"
        >
            Ver JSON-LD
        </button>
        <a href="{{ url('/api/jsonld/medicos') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-external-link-alt me-1"></i> API Directa
        </a>
    </div>
</div>

<!-- Contenedor JSON-LD (oculto por defecto) -->
<div id="jsonLdContainer" class="json-ld-container" style="display: none;">
    <h5><i class="fas fa-code me-2"></i>Respuesta JSON-LD:</h5>
    <div id="jsonLdContent">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<!-- Lista de médicos -->
<div class="row" id="medicosContainer">
    <!-- Los médicos se cargarán aquí dinámicamente -->
    <div class="col-12">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando médicos...</span>
            </div>
            <p class="mt-2">Cargando lista de médicos...</p>
        </div>
    </div>
</div>

<!-- Modal para vista rápida de JSON-LD de médico individual -->
<div class="modal fade" id="medicoJsonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code me-2"></i>
                    JSON-LD del Médico
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalJsonContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status"></div>
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
// Cargar médicos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadMedicos();
});

async function loadMedicos() {
    try {
        const response = await fetch('/api/jsonld/medicos');
        const data = await response.json();
        
        const container = document.getElementById('medicosContainer');
        
        if (data.itemListElement && data.itemListElement.length > 0) {
            let html = '';
            
            data.itemListElement.forEach(function(listItem) {
                const medico = listItem.item;
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 card-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="card-title mb-1">${medico.name || 'Médico #' + medico.identifier}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-stethoscope me-1"></i>
                                            ${medico.medicalSpecialty?.name || 'Medicina General'}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="card-text">
                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                        ${medico.email || 'Email no disponible'}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        ${medico.telephone || 'Teléfono no disponible'}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                        ${medico.workLocation?.name || 'Consultorio General'}
                                    </p>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="/medicos/${medico.identifier}" class="btn btn-primary">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Más Información
                                    </a>
                                    <button 
                                        class="btn btn-outline-secondary btn-sm" 
                                        onclick="showMedicoJson('${medico.identifier}', '${medico.name}')"
                                    >
                                        <i class="fas fa-code me-1"></i>
                                        Ver JSON-LD
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No se encontraron médicos registrados.
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al cargar médicos:', error);
        document.getElementById('medicosContainer').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar la lista de médicos: ${error.message}
                </div>
            </div>
        `;
    }
}

// Mostrar JSON-LD de médico individual en modal
async function showMedicoJson(medicoId, medicoName) {
    const modal = new bootstrap.Modal(document.getElementById('medicoJsonModal'));
    
    // Actualizar título del modal
    document.querySelector('#medicoJsonModal .modal-title').innerHTML = 
        `<i class="fas fa-code me-2"></i>JSON-LD: ${medicoName}`;
    
    // Actualizar enlace de API
    document.getElementById('modalApiLink').href = `/api/jsonld/medicos/${medicoId}`;
    
    // Mostrar modal
    modal.show();
    
    // Cargar datos JSON-LD
    try {
        const response = await fetch(`/api/jsonld/medicos/${medicoId}`);
        const data = await response.json();
        
        document.getElementById('modalJsonContent').innerHTML = 
            '<pre><code>' + JSON.stringify(data, null, 2) + '</code></pre>';
    } catch (error) {
        document.getElementById('modalJsonContent').innerHTML = 
            '<div class="alert alert-danger">Error al cargar datos: ' + error.message + '</div>';
    }
}
</script>
@endpush