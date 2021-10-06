<?php

namespace SertxuDeveloper\Lyra\Pagination;

use Illuminate\Pagination\LengthAwarePaginator as IlluminateLengthAwarePaginator;
use Illuminate\Support\Collection;

class LengthAwarePaginator extends IlluminateLengthAwarePaginator {

  /**
   * Get the paginator links as a collection (for JSON responses).
   *
   * @return Collection
   */
  public function linkCollection() {
    return collect($this->elements())->flatMap(function ($item) {
      if (!is_array($item)) {
        return [['url' => null, 'label' => '...', 'active' => false]];
      }

      return collect($item)->map(function ($url, $page) {
        return [
          'url' => $url,
          'label' => (string)$page,
          'active' => $this->currentPage() === $page,
          'page' => (int)$page,
        ];
      });
    })->prepend([
      'url' => $this->previousPageUrl(),
      'label' => function_exists('__') ? __('pagination.previous') : 'Previous',
      'active' => false,
      'page' => $this->currentPage() > 1 ? $this->currentPage() - 1 : null,
    ])->push([
      'url' => $this->nextPageUrl(),
      'label' => function_exists('__') ? __('pagination.next') : 'Next',
      'active' => false,
      'page' => $this->hasMorePages() ? $this->currentPage() + 1 : null,
    ]);
  }

  /**
   * Get the instance as an array.
   *
   * @return array
   */
  public function toArray() {
    return [
      'current_page' => $this->currentPage(),
      'data' => $this->items->toArray(),
      'first_page_url' => $this->url(1),
      'from' => $this->firstItem(),
      'last_page' => $this->lastPage(),
      'last_page_url' => $this->url($this->lastPage()),
      'links' => $this->linkCollection()->toArray(),
      'next_page_url' => $this->nextPageUrl(),
      'path' => $this->path(),
      'per_page' => $this->perPage(),
      'prev_page_url' => $this->previousPageUrl(),
      'to' => $this->lastItem(),
      'total' => $this->total(),
    ];
  }
}
