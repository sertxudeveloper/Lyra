<?php

namespace SertxuDeveloper\Lyra\Pagination;

use Illuminate\Pagination\LengthAwarePaginator as IlluminateLengthAwarePaginator;
use Illuminate\Support\Collection;

class LengthAwarePaginator extends IlluminateLengthAwarePaginator
{
    /**
     * The number of links to display on each side of current page link.
     *
     * @var int
     */
    public $onEachSide = 3;

    /**
     * Get the paginator links as a collection (for JSON responses).
     *
     * @return Collection
     */
    public function linkCollection(): Collection
    {
        $middlePage = min(max($this->onEachSide, $this->currentPage()), $this->lastPage() - $this->onEachSide + 1);
        $fromPage = max($middlePage - $this->onEachSide + 1, 1);
        $toPage = min($middlePage + $this->onEachSide - 1, $this->lastPage());

        $elements = ['first' => $this->getUrlRange($fromPage, $toPage)];

        return collect($elements)->flatMap(function ($item) {
//      if (!is_array($item)) {
//        return [['url' => null, 'label' => '...', 'active' => false]];
//      }

            return collect($item)->map(function ($url, $page) {
                return [
                    'url' => $url,
                    'label' => (string) $page,
                    'active' => $this->currentPage() === $page,
                    'page' => (int) $page,
                ];
            });
        })->prepend([
            'url' => $this->previousPageUrl(),
            'label' => 'First',
            'active' => false,
            'page' => 1,
        ])->push([
            'url' => $this->nextPageUrl(),
            'label' => 'Last',
            'active' => false,
            'page' => $this->lastPage(),
        ]);
    }
}
