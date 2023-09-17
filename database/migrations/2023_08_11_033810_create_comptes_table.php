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
        //`NumeroCompte`, `idClient`, `mise`, `typeCompte`, `devise`, `total`, `dette`, `MontantRetire`, 
        //`CommissionTouche`, `CycleR`, `CycleD`, `NbrCycle`, `Cloture`
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->string('NumeroCompte')->nullable();
         // $table->integer('client_id');
            $table->integer('mise');
            $table->string('typeCompte');
            $table->string('devise');
            $table->integer('total')->nullable();
            $table->integer('dette')->nullable();
            $table->integer('MontantRetire')->nullable();
            $table->integer('CommissionTouche')->nullable();
            $table->integer('CycleR')->nullable();
            $table->integer('CycleD')->nullable();
            $table->integer('NbrCycle')->nullable();
            $table->integer('Cloture')->nullable();

        //  $table->foreign('idClient')->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('client_id')->constrained();
          
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
        Schema::dropIfExists('comptes');
    }
};
