<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CategoryUpdateRequest extends FormRequest
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
    * Prepare the data for validation if the data comes in json format
    *
    * @return void
    */
    /*
    protected function prepareForValidation()
    {
        collect(json_decode($this->data, true))->each(function ($row, $key) {
            $this->merge([$key => $row]);
        });
    }
    */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|max:255',
            'description' => 'sometimes|required|max:255',
            'icon' => 'sometimes|required|image',
        ];
    }

    protected function formatErrors (Validator $validator)
    {
        return ['message' => $validator->errors()->first()];
    }
}
