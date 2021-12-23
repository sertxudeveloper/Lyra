<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use JetBrains\PhpStorm\ArrayShape;

class Pagination extends PaginatedResourceResponse {

  /**
   * Add the pagination information to the response.
   *
   * @param Request $request
   *
   * @return array
   */
  #[ArrayShape(['meta' => "array"])]
  protected function paginationInformation($request): array {
    $paginated = $this->resource->resource->toArray();

    return [
      'meta' => $this->meta($paginated),
    ];
  }
}
