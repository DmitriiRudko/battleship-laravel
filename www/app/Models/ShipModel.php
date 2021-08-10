<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\ShipModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel query()
 * @mixin \Eloquent
 */
class ShipModel extends Model {
    use HasFactory;

    protected $table = 'warships';

}
