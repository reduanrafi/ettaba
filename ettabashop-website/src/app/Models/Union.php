<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }
}
