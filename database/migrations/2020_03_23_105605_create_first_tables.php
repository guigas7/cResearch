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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('ventilator')->nullable();
            $table->string('name', 100)->nullable();
            $table->boolean('estudo');
            $table->timestamp('inserted_on')->nullable();
            $table->timestamps();
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
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('patients');

    }
}
