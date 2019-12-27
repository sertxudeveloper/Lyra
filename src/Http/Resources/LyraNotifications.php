<?php

namespace SertxuDeveloper\Lyra\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LyraNotifications extends JsonResource {

  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'title' => $this->data['title'],
      'message' => $this->data['message'],
      'read' => (bool)$this->read_at,
      'date' => $this->created_at->diffForHumans(null, null, false, 2),
    ];
  }

}
