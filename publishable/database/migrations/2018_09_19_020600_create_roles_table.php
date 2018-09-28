<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('roles', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 50)->unique();
      $table->string('display_name', 150);
      $table->datetime('created_at')->nullable();
      $table->datetime('updated_at')->nullable();
      $table->datetime('deleted_at')->nullable();
    });
  }

  /**
   * Reverse the migration.
   *
   * @return void
   */
  public function down() {
    Schema::drop('roles');
  }
}