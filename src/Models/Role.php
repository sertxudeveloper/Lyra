<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model {

  use SoftDeletes;
  protected $table = 'lyra_roles';
  protected $guarded = [];

  /**
   * Get the permissions of the role
   * @return \Illuminate\Database\Eloquent\Relations\hasMany
   */
  public function permissions() {
    return $this->hasMany(Permission::class);
  }

  public function users() {
    return $this->hasMany(User::class);
  }
}
