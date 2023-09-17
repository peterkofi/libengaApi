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

    //'nom','prenom','photoProfil','adresse','type','etat','email','pass','Autorisation'
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('photoProfil')->nullable();
            $table->string('adresse');
            $table->integer('type');
            $table->integer('etat');
            $table->string('email')->unique();
            $table->string('pass');
            $table->integer('Autorisation');
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
        Schema::dropIfExists('agents');
    }
};
