<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusResource extends JsonResource
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

            'status' => $this->status,
            'message' => $this->Stats($this->status),
            'date' => $this->created_at->format('Y-m-d H:i A')
        ];
    }

    public function Stats($status)
    {
        if ($status=='accepted')
        {
            $status='Your order is accepted and we are processing your order';
        }
        if ($status=='on_delivery')
        {
            $status='Your order is on way ';
        }
        if ($status=='delivered')
        {
            $status='order delivered successfully';
        }
        if ($status=='canceled')
        {
            $status='Your order being canceled ';
        }
        return $status;
    }
}
