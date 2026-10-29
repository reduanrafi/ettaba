<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    use CommonFunctions;

    protected $fillable = [
        'title_en',
        'title_bn',
        'description_en',
        'description_bn',
        'button_text',
        'button_link',
        'offer_details',
        'sort_order',
        'is_active',
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetData($data)
    {
        if (isset($data['image']) && is_object($data['image'])) {
            $data['image'] = $this->UploadImage($data['image'], 'slider', 500, 1200);
        }
        $data['is_active'] = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $data['sort_order'] = isset($data['sort_order']) ? (int)$data['sort_order'] : 0;
        return $data;
    }
}
