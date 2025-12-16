<?php
// database/fix_especialidad_ids.php
// Script para corregir IDs de especialidad en médicos (YA EJECUTADO)
// Este script fue ejecutado el [FECHA] para corregir desfase de 100 unidades

echo "Este script YA FUE EJECUTADO el [PON LA FECHA AQUÍ]\n";
echo "Los IDs de especialidad en médicos ya fueron corregidos.\n";
echo "No es necesario ejecutarlo nuevamente.\n\n";

echo "Para verificar:\n";
echo "1. http://127.0.0.1:8000/especialidades/1 (debería mostrar médicos)\n";
echo "2. Los médicos ahora tienen especialidad_id entre 1-400 (no 101-500)\n";