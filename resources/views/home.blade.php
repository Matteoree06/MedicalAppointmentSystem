@extends('layouts.app')

@section('title', 'Inicio - Sistema de Citas Médicas')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Hero Section -->
        <div class="jumbotron bg-primary text-white rounded-3 p-5 mb-4">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">
                    <i class="fas fa-stethoscope me-3"></i>
                    Sistema de Citas Médicas
                </h1>
                <p class="col-md-8 fs-4">Demostración de Web Semántica usando JSON-LD con Schema.org en Laravel</p>
                <a href="{{ url('/medicos') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-user-md me-2"></i>
                    Ver Médicos Disponibles
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Módulos principales -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Gestión de Citas</h5>
                <p class="card-text">Administra y consulta las citas médicas programadas.</p>
                <a href="{{ url('/citas') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-check me-1"></i> Ver Citas
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body text-center">
                <i class="fas fa-user-md fa-3x text-success mb-3"></i>
                <h5 class="card-title">Directorio Médico</h5>
                <p class="card-text">Consulta información detallada de médicos y especialidades.</p>
                <a href="{{ url('/medicos') }}" class="btn btn-success">
                    <i class="fas fa-user-md me-1"></i> Ver Médicos
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body text-center">
                <i class="fas fa-heartbeat fa-3x text-danger mb-3"></i>
                <h5 class="card-title">Especialidades Médicas</h5>
                <p class="card-text">Explora las diferentes especialidades disponibles.</p>
                <a href="{{ url('/especialidades') }}" class="btn btn-danger">
                    <i class="fas fa-heartbeat me-1"></i> Ver Especialidades
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-info mb-3"></i>
                <h5 class="card-title">Gestión de Pacientes</h5>
                <p class="card-text">Administra información de pacientes y historiales.</p>
                <a href="{{ url('/pacientes') }}" class="btn btn-info">
                    <i class="fas fa-users me-1"></i> Ver Pacientes
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sección JSON-LD Demo -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-code me-2"></i>
                    APIs JSON-LD Disponibles
                </h4>
            </div>
            <div class="card-body">
                <p class="card-text">Este sistema implementa Web Semántica usando JSON-LD con vocabularios de Schema.org:</p>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ url('/api/jsonld/medicos') }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-user-md me-1"></i> Médicos JSON-LD
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ url('/api/jsonld/users') }}" target="_blank" class="btn btn-outline-success">
                                <i class="fas fa-users me-1"></i> Usuarios JSON-LD
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-grid">
                            <a href="{{ url('/api/jsonld/pacientes') }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-user-injured me-1"></i> Pacientes JSON-LD
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los enlaces abren las respuestas JSON-LD directamente en el navegador. 
                    También puedes probarlos en Postman o cualquier cliente HTTP.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection