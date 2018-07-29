<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LawcaseCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'                              => $this->id,
			'case_uuid'                       => $this->case_uuid,
			'status'                          => $this->status,
			'type'                            => $this->type,
			'number'                          => $this->number,
			'name'                            => $this->name,
			'description'                     => $this->description,
			'court_name'                      => $this->court_name,
			'opposing_councel'                => $this->opposing_councel,
			'claim_reference_number'          => $this->claim_reference_number,
			'location'                        => $this->location,
			'open_date'                       => $this->open_date,
			'close_date'                      => $this->close_date,
			'created_at'                      => $this->created_at,
			'statute_of_limitations'          => $this->statute_of_limitations,
			'created_at'                      => $this->created_at,
			'open_date'                       => $this->open_date,
			'close_date'                      => $this->close_date,
			'is_billable'                     => $this->is_billable,
			'billing_type'                    => $this->billing_type,
			'rate_type'                       => $this->rate_type,
			'billing_rate'                    => $this->billing_rate,
			'hours'                           => $this->hours,
			'order_id'                        => $this->order_id,
			'firm_id'                         => $this->firm_id,
			'u_id'                            => $this->u_id,
		];
	}
}
