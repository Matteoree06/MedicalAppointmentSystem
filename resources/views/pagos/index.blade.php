@extends('layouts.app')

@section('content')
<h1>Pagos</h1>

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Invoice",
  "description": "Factura por cita médica",
  "paymentStatus": "https://schema.org/PaymentComplete"
}
</script>
@endverbatim

<p>Información de pagos y facturación.</p>
@endsection
