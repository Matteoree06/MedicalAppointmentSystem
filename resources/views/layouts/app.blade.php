<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Medical Appointment System</title>
</head>
<body>

<nav>
    <a href="/pacientes">Pacientes</a> |
    <a href="/historial">Historial</a> |
    <a href="/pagos">Pagos</a>
</nav>

<hr>

{{-- AQUÍ SE INYECTA EL CONTENIDO --}}
@yield('content')

</body>
</html>
