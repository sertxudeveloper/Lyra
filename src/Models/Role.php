<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model {
  use SoftDeletes;

  protected $guarded = [];

  /**
   * Get the permissions of the role
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function permissions() {
    return $this->belongsToMany(Permission::class, 'permission_role');
  }
}