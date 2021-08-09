<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\UserModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel query()
 * @mixin \Eloquent
 */
class UserModel extends Model {
    use HasFactory;

    public function newUser(): array {
        $userCode = uniqid();

        $userId = DB::table('users')->insertGetId([
            'code' => $userCode,
        ]);

        return [
            'id' => $userId,
            'code' => $userCode,
        ];
    }

    public function getUserInfoByCode($code): array {
        $userInfo = (array) DB::table('users')->where('code', $code)->first();
        return $userInfo;
    }
}
