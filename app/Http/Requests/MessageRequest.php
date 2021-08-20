<?php

namespace App\Http\Requests;

use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'id'      => 'numeric',
            'code'    => 'alpha_num|size:13',
            'message' => 'filled',
        ];
    }

    protected function prepareForValidation() {
        $this->message = htmlspecialchars(mb_strimwidth($this->message, 0, Message::MESSAGE_MAX_LEN));
    }
}
