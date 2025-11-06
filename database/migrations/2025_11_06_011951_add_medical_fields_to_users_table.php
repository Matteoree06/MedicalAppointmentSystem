<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos médicos obligatorios
            $table->date('fecha_nacimiento')->after('email_verified_at');
            $table->enum('sexo', ['Masculino', 'Femenino', 'Otro'])->after('fecha_nacimiento');
            $table->string('numero_seguro', 50)->nullable()->after('sexo');
            $table->text('historial_medico')->nullable()->after('numero_seguro');
            $table->string('contacto_emergencia', 20);
            
            // Campos adicionales útiles para sistema médico
            $table->string('telefono', 20)->nullable()->after('contacto_emergencia');
            $table->text('direccion')->nullable()->after('telefono');
            $table->string('tipo_sangre', 5)->nullable()->after('direccion');
            $table->text('alergias')->nullable()->after('tipo_sangre');
            $table->boolean('activo')->default(true)->after('alergias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_nacimiento',
                'sexo',
                'numero_seguro',
                'historial_medico',
                'contacto_emergencia',
                'telefono',
                'direccion',
                'tipo_sangre',
                'alergias',
                'activo'
            ]);
        });
    }
};
