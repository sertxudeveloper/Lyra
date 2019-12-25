<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyraPermissionsTable extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('lyra_permissions', function (Blueprint $table) {
      $table->integer('id');
      $table->integer('role_id');
      $table->string('resource_key', 50);
      $table->string('action', 50);
      $table->timestamps();

      $table->primary(['role_id', 'resource_key', 'action']);
    });
  }

  /**
   * Reverse the migration.
   *
   * @return void
   */
  public function down() {
    Schema::drop('lyra_permissions');
  }
}
