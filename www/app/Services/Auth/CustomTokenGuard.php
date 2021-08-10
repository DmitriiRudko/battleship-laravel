<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class CustomTokenGuard extends \Illuminate\Auth\TokenGuard {
    public function __construct(
        UserProvider $provider,
        Request      $request,
                     $inputKey = '',
                     $storageKey = 'code',
                     $hash = false
    ) {
        parent::__construct($provider, $request, $inputKey, $storageKey);
    }

    public function getTokenForRequest() {
        $token = $this->request->segment(4);
        return $token;
    }
}
