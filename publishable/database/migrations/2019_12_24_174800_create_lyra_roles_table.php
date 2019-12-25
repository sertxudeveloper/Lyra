<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyraRolesTable extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('lyra_roles', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->unique();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migration.
   *
   * @return void
   */
  public function down() {
    Schema::drop('lyra_roles');
  }
}
