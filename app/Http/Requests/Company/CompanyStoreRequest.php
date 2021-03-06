<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CompanyStoreRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'about' => 'required',
            'logo' => 'sometimes|required|mimes:jpeg,jpg,png,gif|max:10000',
            'website' => 'sometimes|required|url',
        ];
    }

    protected function formatErrors (Validator $validator)
    {
        return ['message' => $validator->errors()->first()];
    }
}
