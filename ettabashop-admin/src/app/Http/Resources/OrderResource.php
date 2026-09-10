<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id' => $this->id,
            'uniqe_order_id' => $this->unique_order_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'deliveryCharge'=>$this->delivery_charge==null?0:$this->delivery_charge,
            'paymentMethod' => $this->paymentMethod ? $this->paymentMethod->name: '',
            'received' => new AddressResource($this->address),
            'count' => $this->orderItems->count(),
            'items' => OrderItemsResource::collection($this->orderItems),
            'date' => $this->created_at->format('Y-m-d H:i A')
        ];
    }
}
