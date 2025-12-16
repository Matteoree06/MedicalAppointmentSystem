@extends('layouts.app')

@section('title', 'Especialidades Médicas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Especialidades</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-heartbeat text-danger me-2"></i>
        Especialidades Médicas
    </h1>
    
    <div>
        <button 
            id="toggleJsonLdBtn" 
            class="btn btn-outline-danger" 
            onclick="toggleJsonLd('toggleJsonLdBtn', 'jsonLdContainer'); loadJsonLd('/api/jsonld/especialidades', 'jsonLdContent')"
        >
            Ver JSON-LD
        </button>
        <a href="{{ url('/api/jsonld/especialidades') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-external-link-alt me-1"></i> API Directa
        </a>
    </div>
</div>

<!-- Contenedor JSON-LD -->
<div id="jsonLdContainer" class="json-ld-container" style="display: none;">
    <h5><i class="fas fa-code me-2"></i>Respuesta JSON-LD:</h5>
    <div id="jsonLdContent">
        <div class="text-center">
            <div class="spinner-border text-danger" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<!-- Lista de especialidades -->
<div class="row" id="especialidadesContainer">
    <div class="col-12">
        <div class="text-center">
            <div class="spinner-border text-danger" role="status">
                <span class="visually-hidden">Cargando especialidades...</span>
            </div>
            <p class="mt-2">Cargando lista de especialidades...</p>
        </div>
    </div>
</div>

<!-- Modal para JSON-LD individual -->
<div class="modal fade" id="especialidadJsonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code me-2"></i>
                    JSON-LD de la Especialidad
                </h5>
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
// Cargar especialidades al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadEspecialidades();
});

async function loadEspecialidades() {
    try {
        const response = await fetch('/api/jsonld/especialidades');
        const data = await response.json();
        
        const container = document.getElementById('especialidadesContainer');
        
        if (data.itemListElement && data.itemListElement.length > 0) {
            let html = '';
            
            data.itemListElement.forEach(function(listItem) {
                const especialidad = listItem.item;
                
                // Contar médicos de esta especialidad
                const medicosCount = especialidad.hasPhysician ? especialidad.hasPhysician.length : 0;
                
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 card-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-heartbeat"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="card-title mb-1">${especialidad.name || 'Especialidad Médica'}</h5>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-user-md me-1"></i>
                                            ${medicosCount} médico(s)
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="card-text">
                                        ${especialidad.description || 'Sin descripción disponible'}
                                    </p>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="/especialidades/${especialidad.identifier}" class="btn btn-danger">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ver Detalles
                                    </a>
                                    <button 
                                        class="btn btn-outline-secondary btn-sm" 
                                        onclick="showEspecialidadJson('${especialidad.identifier}', '${especialidad.name}')"
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
                        No se encontraron especialidades registradas.
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al cargar especialidades:', error);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar especialidades: ${error.message}
                </div>
            </div>
        `;
    }
}

// Mostrar JSON-LD de especialidad individual
async function showEspecialidadJson(especialidadId, especialidadName) {
    const modal = new bootstrap.Modal(document.getElementById('especialidadJsonModal'));
    
    document.querySelector('#especialidadJsonModal .modal-title').innerHTML = 
        `<i class="fas fa-code me-2"></i>JSON-LD: ${especialidadName}`;
    
    document.getElementById('modalApiLink').href = `/api/jsonld/especialidades/${especialidadId}`;
    
    modal.show();
    
    try {
        const response = await fetch(`/api/jsonld/especialidades/${especialidadId}`);
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