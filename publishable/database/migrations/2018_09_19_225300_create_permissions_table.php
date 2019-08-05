<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('lyra_permissions', function (Blueprint $table) {
      $table->increments('id');
      $table->string('key', 50)->unique();
      $table->string('table_name', 50)->nullable();
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
    Schema::drop('lyra_permissions');
  }
}
