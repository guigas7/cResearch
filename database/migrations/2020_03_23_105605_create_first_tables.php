<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::enableForeignKeyConstraints();

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->unsignedBigInteger('hospital_id')->nullable();     
            $table->boolean('ventilator')->nullable();
            $table->string('prontuario', 50)->nullable();
            $table->string('slug', 60)->unique()->nullable();
            $table->boolean('study');
            $table->timestamp('inserted_on')->nullable();
            $table->timestamps();
        });

        Schema::table('patients', function($table) {
            $table->foreign('hospital_id')->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign('patients_hospital_id_foreign');
        });
        Schema::dropIfExists('patients');

    }
}
