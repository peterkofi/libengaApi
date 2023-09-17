<?php

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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('NumeroClient');
            $table->string('nom');
            $table->string('prenom');
            $table->string('Delegue1')->nullable();
            $table->string('Delegue2')->nullable();
            $table->string('photoProfil')->nullable();
            $table->string('photoSignature')->nullable();
            $table->string('NumeroTelephone')->nullable();
            $table->string('adresse');
            $table->string('DateNaissance');
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
        Schema::dropIfExists('clients');
    }
};
