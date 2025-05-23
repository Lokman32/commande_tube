<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {

    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('matricule');
      $table->string('role')->default('polyvalent');
      $table->timestamps();
    });

    Schema::create('tubes', function (Blueprint $table) {
      $table->id(); // id('serial_id') pas encore modifier  
      $table->string('dpn');
      $table->string('type');
      $table->integer('packaging');
      $table->string('unity');
      $table->string('rack');
      $table->timestamps();
    });

    Schema::create('commandes', function (Blueprint $table) {
      $table->string('barcode')->primary();
      $table->foreignId('command_by')->nullable()->constrained('users', 'id');
      $table->string('status')->default('en_attente');
      $table->timestamps();
    });

    Schema::create('ligne_commande', function (Blueprint $table) {
      $table->uuid('serial_cmd');
      $table->unsignedBigInteger('tube_id');
      $table->integer('quantity');
      $table->string('rack');
      $table->string('statut');
      $table->integer('retard');
      $table->text('description');
      $table->timestamps();
    
      $table->primary(['serial_cmd', 'tube_id']);
      $table->foreign('serial_cmd')->references('barcode')->on('commandes');
      $table->foreign('tube_id')->references('id')->on('tubes');
    });

    Schema::create('validations', function (Blueprint $table) {
      $table->id();
      $table->string('commande_id');
      $table->foreign('commande_id')->references('barcode')->on('commandes')->onDelete('cascade');
      $table->unsignedBigInteger('tube_id');
      $table->foreign('tube_id')->references('id')->on('tubes')->onDelete('cascade');
      $table->string('serial_product')->unique();
      $table->timestamp('validated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
  });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('validations');
    Schema::dropIfExists('emplacement_tube');
    Schema::dropIfExists('ligne_commande');
    Schema::dropIfExists('commandes');
    Schema::dropIfExists('tubes');
    Schema::dropIfExists('users');
  }
};
