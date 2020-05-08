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

        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('cl')->default(1);
            $table->boolean('pr')->default(1);
            $table->string('slug', 110)->unique()->nullable();
            $table->timestamps();
        }); 

        Schema::create('clpatients', function (Blueprint $table) {
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

        Schema::create('prpatients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->unsignedBigInteger('hospital_id')->nullable();     
            $table->string('prontuario', 50)->nullable();
            $table->string('slug', 60)->unique()->nullable();
            $table->boolean('study');
            $table->timestamp('inserted_on')->nullable();
            $table->timestamps();
        });

        Schema::table('clpatients', function($table) {
            $table->foreign('hospital_id')->references('id')->on('hospitals')
                ->onDelete('set null');
        });

        Schema::table('prpatients', function($table) {
            $table->foreign('hospital_id')->references('id')->on('hospitals')
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
        Schema::table('clpatients', function (Blueprint $table) {
            $table->dropForeign('clpatients_hospital_id_foreign');
        });
        Schema::dropIfExists('clpatients');

        Schema::table('prpatients', function (Blueprint $table) {
            $table->dropForeign('prpatients_hospital_id_foreign');
        });
        Schema::dropIfExists('prpatients');

        Schema::dropIfExists('hospitals');

    }
}
