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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();

            // Quitamos la clave foránea aquí para evitar la referencia circular
        });

        // Insertar algunos departamentos de ejemplo
        DB::table('departments')->insert([
            ['name' => 'RRHH', 'description' => 'Recursos Humanos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Desarrollo', 'description' => 'Equipo de desarrollo de software', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marketing', 'description' => 'Departamento de marketing', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
};
