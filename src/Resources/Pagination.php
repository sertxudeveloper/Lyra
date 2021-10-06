<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class Pagination extends PaginatedResourceResponse {


  /**
   * Add the pagination information to the response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  protected function paginationInformation($request)
  {
    $paginated = $this->resource->resource->toArray();

    return [
      'meta' => $this->meta($paginated),
    ];
  }
}
