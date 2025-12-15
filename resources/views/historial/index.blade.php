@extends('layouts.app')

@section('content')
<h1>Historial Médico</h1>

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "MedicalRecord",
  "description": "Historial médico del paciente",
  "about": {
    "@type": "Patient",
    "name": "Paciente del sistema"
  }
}
</script>
@endverbatim

<p>Historial médico completo del paciente.</p>
@endsection
