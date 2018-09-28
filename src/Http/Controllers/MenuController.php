<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Support\Collection;

class MenuController extends Controller {

  /**
   * Get the menu collection
   * @return Collection
   */
  public function getMenu(): Collection {
    $items = collect(config('lyra.menu'));

    return $items->filter(function (&$item) {
      return $this->can($item);
    })->map(function ($item, $key) {
      $item['link'] = $this->link($item, true);
      return $item;
    });
  }

  /**
   * Check if user can access to the $item
   * @param array $item
   * @return bool
   */
  private function can(array $item): bool {
    $regex = str_replace('/', '\/', preg_quote(route(config('lyra.routes.name') . 'dashboard')));
    $slug = preg_replace('/' . $regex . '/', '', $this->link($item, true));
    $slug = str_replace('/', '', $slug);

    $slug = ($slug == "") ? 'lyra' : $slug;

    return auth()->user()->hasPermission('read_' . $slug);
  }

  /**
   * Return a relative|absolute url to the current $item menu
   * @param array $item
   * @param bool $absolute
   * @return \Illuminate\Contracts\Routing\ UrlGenerator|string
   */
  public static function link(array $item, $absolute = false): string {
    if (isset($item['url']) && $item['url']) {
      return $absolute ? url($item['url']) : $item['url'];
    } else if (isset($item['route']) && $item['route']) {
      return route($item['route'], null, $absolute);
    }
    return abort('404', "MenuItem {$item['name']} has no url or route defined");
  }

}