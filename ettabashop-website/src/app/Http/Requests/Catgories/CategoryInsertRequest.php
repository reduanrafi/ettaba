<?php

namespace App\Http\Requests\Catgories;

use Illuminate\Foundation\Http\FormRequest;

class CategoryInsertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //dd(request()->all());
        return [
            'name_en'=>['required'],
            'name_bn'=>['required']
            //'image'=>'required'
        ];
    }
}
