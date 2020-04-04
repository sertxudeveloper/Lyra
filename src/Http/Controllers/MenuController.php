<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Lyra;

class MenuController extends Controller {

  /**
   * Get the menu collection
   *
   * @return array
   */
  public function getMenu(): array {
    return $this->buildMenu(config('lyra.menu'));
  }

  /**
   * @param $items
   * @return array
   */
  private function buildMenu($items) {
    $menu = [];

    foreach ($items as $item) {
      if (isset($item['hidden']) && $item['hidden'] === true) continue;

      if (!isset($item['key'])) $item['key'] = Str::snake($item['name']);

      if (isset($item['resource'])) {
        unset($item['resource']);
        $item['prefix'] = '/resources';
      } else if (isset($item['component'])) {
        $item['prefix'] = '/components';
      } else {
        $item['prefix'] = '';
      }

      if (isset($item['items'])) {
        $item['items'] = $this->buildMenu($item['items']);
        if (count($item['items'])) $menu[] = $item;
      } else {
        if ($this->can($item)) $menu[] = $item;
      }

    }

    return $menu;
  }

  /**
   * Check if user can access the item
   *
   * @param array $item
   * @return bool
   */
  private function can(array $item): bool {
    if (config('lyra.authenticator') === Lyra::MODE_BASIC) return true;
    return Lyra::auth()->user()->hasPermission('read', $item['key']);
  }
}
