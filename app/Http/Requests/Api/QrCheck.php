<?php

namespace MMC\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class QrCheck extends FormRequest
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
        return [
            'id' => 'required',
            'sum_from' => 'required',
            'status' => 'required',
            'status_datetime' => 'required'
        ];
    }
}
