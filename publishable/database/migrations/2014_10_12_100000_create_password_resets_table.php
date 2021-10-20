<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyraPasswordResetsTable extends Migration {
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('lyra_password_resets');
  }

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('lyra_password_resets', function (Blueprint $table) {
      $table->string('email')->index();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });
  }
}
