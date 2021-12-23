<?php

namespace SertxuDeveloper\Lyra\Tests;

use Illuminate\Support\ServiceProvider;
use SertxuDeveloper\Lyra\Lyra;

class TestServiceProvider extends ServiceProvider {

  /**
   * Bootstrap any package services.
   *
   * @return void
   */
  public function boot() {
    Lyra::routes(auth: true);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register() {
    //
  }
}
