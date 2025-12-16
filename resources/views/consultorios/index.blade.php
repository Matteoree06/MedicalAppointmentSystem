@extends('layouts.app')

@section('title', 'Consultorios y Salas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Consultorios</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-hospital text-success me-2"></i>
        Consultorios y Salas
    </h1>
    
    <div>
        <button 
            id="toggleJsonLdBtn" 
            class="btn btn-outline-success" 
            onclick="toggleJsonLd('toggleJsonLdBtn', 'jsonLdContainer'); loadJsonLd('/api/jsonld/consultorios', 'jsonLdContent')"
        >
            Ver JSON-LD
        </button>
        <a href="{{ url('/api/jsonld/consultorios') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-external-link-alt me-1"></i> API Directa
        </a>
    </div>
</div>

<!-- Contenedor JSON-LD -->
<div id="jsonLdContainer" class="json-ld-container" style="display: none;">
    <h5><i class="fas fa-code me-2"></i>Respuesta JSON-LD:</h5>
    <div id="jsonLdContent">
        <div class="text-center">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<!-- Lista de consultorios -->
<div class="row" id="consultoriosContainer">
    <div class="col-12">
        <div class="text-center">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Cargando consultorios...</span>
            </div>
            <p class="mt-2">Cargando lista de consultorios...</p>
        </div>
    </div>
</div>

<!-- Modal para JSON-LD individual -->
<div class="modal fade" id="consultorioJsonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code me-2"></i>
                    JSON-LD del Consultorio
                </h5>
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
// Cargar consultorios al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadConsultorios();
});

async function loadConsultorios() {
    try {
        const response = await fetch('/api/jsonld/consultorios');
        const data = await response.json();
        
        const container = document.getElementById('consultoriosContainer');
        
        if (data.itemListElement && data.itemListElement.length > 0) {
            let html = '';
            
            data.itemListElement.forEach(function(listItem) {
                const consultorio = listItem.item;
                
                // Contar médicos asignados
                const medicosCount = consultorio.medicalStaff ? consultorio.medicalStaff.length : 0;
                
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 card-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-hospital"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="card-title mb-1">${consultorio.address?.streetAddress || consultorio.name || 'Consultorio'}</h5>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-user-md me-1"></i>
                                            ${medicosCount} médico(s)
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="card-text">
    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
    ${consultorio.name || 'Consultorio médico'}
</p>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="/consultorios/${consultorio.identifier}" class="btn btn-success">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ver Detalles
                                    </a>
                                    <button 
                                        class="btn btn-outline-secondary btn-sm" 
                                        onclick="showConsultorioJson('${consultorio.identifier}', '${consultorio.name}')"
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
                        No se encontraron consultorios registrados.
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al cargar consultorios:', error);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar consultorios: ${error.message}
                </div>
            </div>
        `;
    }
}

// Mostrar JSON-LD de consultorio individual
async function showConsultorioJson(consultorioId, consultorioName) {
    const modal = new bootstrap.Modal(document.getElementById('consultorioJsonModal'));
    
    document.querySelector('#consultorioJsonModal .modal-title').innerHTML = 
        `<i class="fas fa-code me-2"></i>JSON-LD: ${consultorioName}`;
    
    document.getElementById('modalApiLink').href = `/api/jsonld/consultorios/${consultorioId}`;
    
    modal.show();
    
    try {
        const response = await fetch(`/api/jsonld/consultorios/${consultorioId}`);
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