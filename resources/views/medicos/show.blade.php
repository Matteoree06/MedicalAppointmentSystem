@extends('layouts.app')

@section('title', 'Detalle del Médico')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/medicos') }}">Médicos</a></li>
    <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Encabezado con botones -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">
                    <i class="fas fa-user-md text-primary me-2"></i>
                    <span id="medicoName">Cargando...</span>
                </h1>
                <p class="text-muted mb-0" id="medicoSpecialty">Cargando especialidad...</p>
            </div>
            
            <div>
                <a href="{{ url('/medicos') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Lista
                </a>
                <button 
                    id="toggleJsonLdBtn" 
                    class="btn btn-outline-primary" 
                    onclick="toggleJsonLd('toggleJsonLdBtn', 'jsonLdContainer'); loadMedicoJsonLd()"
                >
                    Ver JSON-LD
                </button>
                <a id="apiDirectLink" href="#" target="_blank" class="btn btn-outline-info">
                    <i class="fas fa-external-link-alt me-1"></i> API Directa
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contenedor JSON-LD (oculto por defecto) -->
<div id="jsonLdContainer" class="json-ld-container" style="display: none;">
    <h5><i class="fas fa-code me-2"></i>Respuesta JSON-LD del Médico:</h5>
    <div id="jsonLdContent">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<!-- Contenido principal -->
<div class="row">
    <!-- Información del médico -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información Personal
                </h5>
            </div>
            <div class="card-body" id="medicoInfo">
                <!-- Información se carga dinámicamente -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Cargando información del médico...</p>
                </div>
            </div>
        </div>
        
        <!-- Citas del médico -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Citas Programadas
                </h5>
            </div>
            <div class="card-body" id="medicoCitas">
                <!-- Citas se cargan dinámicamente -->
                <div class="text-center">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2">Cargando citas...</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel lateral -->
    <div class="col-lg-4">
        <!-- Especialidad -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-stethoscope me-2"></i>
                    Especialidad
                </h6>
            </div>
            <div class="card-body" id="especialidadInfo">
                <div class="text-center">
                    <div class="spinner-border text-info" role="status"></div>
                </div>
            </div>
        </div>
        
        <!-- Consultorio -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Ubicación
                </h6>
            </div>
            <div class="card-body" id="consultorioInfo">
                <div class="text-center">
                    <div class="spinner-border text-warning" role="status"></div>
                </div>
            </div>
        </div>
        
        <!-- Horario -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Horario de Atención
                </h6>
            </div>
            <div class="card-body" id="horarioInfo">
                <div class="text-center">
                    <div class="spinner-border text-secondary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Obtener ID del médico desde la URL
const medicoId = window.location.pathname.split('/').pop();

// Cargar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadMedicoDetalle();
    loadMedicoCitas();
    
    // Configurar enlace directo a API
    document.getElementById('apiDirectLink').href = `/api/jsonld/medicos/${medicoId}`;
});

// Cargar JSON-LD del médico
function loadMedicoJsonLd() {
    loadJsonLd(`/api/jsonld/medicos/${medicoId}`, 'jsonLdContent');
}

// Cargar detalles del médico
async function loadMedicoDetalle() {
    try {
        const response = await fetch(`/api/jsonld/medicos/${medicoId}`);
        const medico = await response.json();
        
        if (response.ok && medico['@type'] === 'Physician') {
            // Actualizar encabezado
            document.getElementById('medicoName').textContent = medico.name || 'Médico #' + medicoId;
            document.getElementById('medicoSpecialty').textContent = medico.medicalSpecialty?.name || 'Medicina General';
            
            // Información personal
            const infoHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-id-badge me-2"></i>ID:</strong> ${medico.identifier}</p>
                        <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> ${medico.email || 'No disponible'}</p>
                        <p><strong><i class="fas fa-phone me-2"></i>Teléfono:</strong> ${medico.telephone || 'No disponible'}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-building me-2"></i>Institución:</strong> ${medico.worksFor?.name || 'No especificada'}</p>
                        <p><strong><i class="fas fa-user-check me-2"></i>Nuevos Pacientes:</strong> 
                            <span class="badge ${medico.isAcceptingNewPatients ? 'bg-success' : 'bg-warning'}">
                                ${medico.isAcceptingNewPatients ? 'Aceptando' : 'No Disponible'}
                            </span>
                        </p>
                        <p><strong><i class="fas fa-calendar me-2"></i>Experiencia:</strong> ${medico.yearsOfExperience || 'No especificado'} años</p>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6><i class="fas fa-certificate me-2"></i>Credenciales:</h6>
                    <p class="text-muted">${medico.hasCredential?.name || 'Licencia Médica'} - Reconocido por ${medico.hasCredential?.recognizedBy?.name || 'Colegio Médico'}</p>
                </div>
            `;
            document.getElementById('medicoInfo').innerHTML = infoHtml;
            
            // Especialidad
            document.getElementById('especialidadInfo').innerHTML = `
                <h6 class="text-info">${medico.medicalSpecialty?.name || 'Medicina General'}</h6>
                <p class="text-muted mb-0">ID: ${medico.medicalSpecialty?.identifier || 'N/A'}</p>
            `;
            
            // Consultorio
            document.getElementById('consultorioInfo').innerHTML = `
                <h6 class="text-warning">${medico.workLocation?.name || 'Consultorio General'}</h6>
                <p class="text-muted mb-0">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    ${medico.workLocation?.address?.addressLocality || 'Ubicación por definir'}
                </p>
            `;
            
            // Horario
            document.getElementById('horarioInfo').innerHTML = `
                <p><strong>Días:</strong> ${medico.schedule?.byDay?.join(', ') || 'Lun - Vie'}</p>
                <p><strong>Horario:</strong> ${medico.schedule?.opens || '08:00'} - ${medico.schedule?.closes || '17:00'}</p>
                <p class="text-muted mb-0"><small>Zona horaria: ${medico.schedule?.scheduleTimezone || 'America/Guayaquil'}</small></p>
            `;
        } else {
            throw new Error('Médico no encontrado');
        }
    } catch (error) {
        console.error('Error al cargar médico:', error);
        document.getElementById('medicoInfo').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Error al cargar información del médico: ${error.message}
            </div>
        `;
    }
}

// Cargar citas del médico
async function loadMedicoCitas() {
    try {
        const response = await fetch(`/api/jsonld/medicos/${medicoId}/citas`);
        const data = await response.json();
        
        if (data.itemListElement && data.itemListElement.length > 0) {
            let html = '<div class="row">';
            
            data.itemListElement.forEach(function(listItem) {
                const cita = listItem.item;
                html += `
                    <div class="col-12 mb-3">
                        <div class="card border-start border-success border-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title">
                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                            ${new Date(cita.appointmentTime).toLocaleString()}
                                        </h6>
                                        <p class="card-text">${cita.description || 'Consulta médica'}</p>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-user me-1"></i>
                                            Paciente: ${cita.patient?.name || 'No especificado'}
                                        </p>
                                    </div>
                                    <span class="badge bg-success">ID: ${cita.identifier}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            document.getElementById('medicoCitas').innerHTML = html;
        } else {
            document.getElementById('medicoCitas').innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay citas programadas para este médico.
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al cargar citas:', error);
        document.getElementById('medicoCitas').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Error al cargar citas: ${error.message}
            </div>
        `;
    }
}
</script>
@endpush