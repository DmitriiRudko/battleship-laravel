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
        if (Auth::user()) {
            return Auth::user()->game->id === (int)$this->id
                && Auth::user()->ready === UserModel::NOT_READY_STATUS
                && Auth::user()->game->status === GameModel::GAME_HAS_NOT_BEGUN_STATUS;
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
        if ($this->ships) {
            return [
            ];
        } elseif ($this->x && $this->y) {
            return [
                'x' => 'digits_between:0,9|required',
                'y' => 'digits_between:0,9|required',
            ];
        } else {
            return [
                'ship' => [new ShipId, 'required'],
            ];
        }
    }
}
