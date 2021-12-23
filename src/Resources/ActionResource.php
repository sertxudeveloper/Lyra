<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class ActionResource extends JsonResource {

  /**
   * @param $request
   *
   * @return array
   */
  #[ArrayShape(['key' => "string", 'name' => "string"])]
  public function toArray($request): array {
    return [
      'key' => $this->resource::slug(),
      'name' => $this->resource::label(),
    ];
  }
}
