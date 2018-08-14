<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id'                              => $this->id,
      'contlient_uuid'                  => $this->contlient_uuid,
      'prefix'                          => $this->prefix,
      'first_name'                      => $this->first_name,
      'last_name'                       => $this->last_name,
      'relationship'                    => $this->relationship,
      'company'                         => $this->company,
      'company_title'                   => $this->company_title,
      'phone'                           => $this->phone,
      'email'                           => $this->email,
      'address_1'                       => $this->address_1,
      'address_2'                       => $this->address_2,
      'city'                            => $this->city,
      'state'                           => $this->state,
      'zip'                             => $this->zip,
      'case_id'                         => $this->case_id,
      'firm_id'                         => $this->firm_id,
      'is_client'                       => $this->is_client,
      'has_login'                       => $this->has_login,
      'user_id'                         => $this->user_id,
      'is_deleted'                      => $this->is_deleted,
      'created_at'                      => $this->created_at,
      'updated_at'                      => $this->updated_at,
      'deleted_at'                      => $this->deleted_at,
    ];    
  }
}
