<?php

namespace App\Http\Resources;

use App\Models\Phone;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PhoneResource
 * @package App\Http\Resources
 * @mixin Phone
 */
class PhoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'state' => $this->state,
            'phone' => preg_replace("/\((\d)*\)/", "", $this->phone),
            'country_code' => $this->getCountryCode(),
            'country_name' => $this->country_name,
        ];
    }


}
