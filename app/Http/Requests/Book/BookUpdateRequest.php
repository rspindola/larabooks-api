<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BookUpdateRequest extends FormRequest
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
            'title' => 'required',
            'cover' => 'sometimes|required|mimes:jpeg,jpg,png,gif|max:10000',
            'description' => 'sometimes|required',
            'about' => 'sometimes',
            'chapters' => 'sometimes|json',
            'gender' => 'required',
            'pages' => 'required',
            'price' => 'required',
            'status' => 'required',
            'published_at' => 'required|date',
        ];
    }

    protected function formatErrors (Validator $validator)
    {
        return ['message' => $validator->errors()->first()];
    }
}
