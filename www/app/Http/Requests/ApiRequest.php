<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\This;

class ApiRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        if (Auth::user()) {
            return Auth::user()->game->id === (int)$this->id;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'id'   => 'numeric|required',
            'code' => 'alpha_num||size:13|required',
        ];
    }
}
