<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistema de Citas Médicas')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS (sin Vite por ahora) -->
    <style>
        /* CSS adicional se incluye directamente aquí */
    </style>
    
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #0d6efd !important;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .json-ld-container {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-stethoscope me-2"></i>
                Sistema de Citas Médicas
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('citas*') ? 'active' : '' }}" href="{{ url('/citas') }}">
                            <i class="fas fa-calendar-check me-1"></i> Citas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('medicos*') ? 'active' : '' }}" href="{{ url('/medicos') }}">
                            <i class="fas fa-user-md me-1"></i> Médicos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('especialidades*') ? 'active' : '' }}" href="{{ url('/especialidades') }}">
                            <i class="fas fa-heartbeat me-1"></i> Especialidades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pacientes*') ? 'active' : '' }}" href="{{ url('/pacientes') }}">
                            <i class="fas fa-users me-1"></i> Pacientes
                        </a>
                    </li>
                </ul>
                
                <!-- JSON-LD APIs -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-code me-1"></i> APIs JSON-LD
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/api/jsonld/medicos') }}" target="_blank">
                                <i class="fas fa-user-md me-1"></i> Médicos JSON-LD
                            </a></li>
                            <li><a class="dropdown-item" href="{{ url('/api/jsonld/users') }}" target="_blank">
                                <i class="fas fa-users me-1"></i> Usuarios JSON-LD
                            </a></li>
                            <li><a class="dropdown-item" href="{{ url('/api/jsonld/pacientes') }}" target="_blank">
                                <i class="fas fa-user-injured me-1"></i> Pacientes JSON-LD
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Breadcrumb -->
    @hasSection('breadcrumb')
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>
        </nav>
    </div>
    @endif
    
    <!-- Main Content -->
    <main class="container my-4">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer bg-light mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Sistema de Citas Médicas</h5>
                    <p class="text-muted">Demostración de Web Semántica con JSON-LD</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">Proyecto ARQ SW - Backend</p>
                    <small class="text-muted">Laravel + JSON-LD + Schema.org</small>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Función para cargar datos JSON-LD
        async function loadJsonLd(url, containerId) {
            try {
                const response = await fetch(url);
                const data = await response.json();
                document.getElementById(containerId).innerHTML = 
                    '<pre><code>' + JSON.stringify(data, null, 2) + '</code></pre>';
            } catch (error) {
                document.getElementById(containerId).innerHTML = 
                    '<div class="alert alert-danger">Error al cargar datos: ' + error.message + '</div>';
            }
        }
        
        // Función para mostrar/ocultar JSON-LD
        function toggleJsonLd(buttonId, containerId) {
            const container = document.getElementById(containerId);
            const button = document.getElementById(buttonId);
            
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'block';
                button.textContent = 'Ocultar JSON-LD';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-outline-secondary');
            } else {
                container.style.display = 'none';
                button.textContent = 'Ver JSON-LD';
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-outline-primary');
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>