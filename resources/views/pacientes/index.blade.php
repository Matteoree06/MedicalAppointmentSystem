@extends('layouts.app')

@section('content')
<h1>Pacientes</h1>

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Patient",
  "name": "Listado de Pacientes",
  "description": "Vista de pacientes del sistema de citas médicas"
}
</script>
@endverbatim

<p>Listado de pacientes cargado desde la API.</p>
@endsection

