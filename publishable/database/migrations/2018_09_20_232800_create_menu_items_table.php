<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration {

  /**
   * Run the migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('menu_items', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('parent_id')->unsigned()->nullable();
      $table->string('name', 30);
      $table->string('url')->nullable();
      $table->string('route', 50)->nullable();
      $table->string('icon', 50)->nullable();
      $table->integer('order')->unsigned();
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
    Schema::drop('menu_items');
  }
}