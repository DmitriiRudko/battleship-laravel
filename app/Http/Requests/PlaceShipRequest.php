<?php

namespace App\Http\Requests;

use App\Models\GameModel;
use App\Models\UserModel;
use App\Rules\ShipId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PlaceShipRequest extends FormRequest {
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
        if ($this->ships) {
            return [
            ];
        } elseif ($this->x && $this->y) {
            return [
                'x' => 'digits_between:0,9|required',
                'y' => 'digits_between:0,9|required',
                'orientation' => 'in:vertical,horizontal',
                'ship' => [new ShipId, 'required'],
            ];
        } else {
            return [
                'ship' => [new ShipId, 'required'],
            ];
        }
    }
}
