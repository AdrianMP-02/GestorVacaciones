<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_recurring')->default(false); // Si se repite cada año
            $table->timestamps();
        });
        
        // Insertar algunos días festivos de ejemplo para España
        DB::table('holidays')->insert([
            ['name' => 'Año Nuevo', 'date' => '2025-01-01', 'is_recurring' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Día de Reyes', 'date' => '2025-01-06', 'is_recurring' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Viernes Santo', 'date' => '2025-04-18', 'is_recurring' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Día del Trabajo', 'date' => '2025-05-01', 'is_recurring' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holidays');
    }
};