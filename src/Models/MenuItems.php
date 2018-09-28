<?php

namespace SertxuDeveloper\Lyra\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItems extends Model {
  use SoftDeletes;

  protected $guarded = [];

  /**
   * Return a relative|absolute url to the current MenuItem
   * @param bool $absolute
   * @return \Illuminate\Contracts\Routing\UrlGenerator|string
   */
  public function link($absolute = false) {
    if ($this->url) {
      return $absolute ? url($this->url) : $this->url;
    } else if ($this->route) {
      return route($this->route, null, $absolute);
    } else {
      abort('404', "MenuItem {$this->name} has no url or route defined");
    }
  }
}