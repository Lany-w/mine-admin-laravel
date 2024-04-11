<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:29
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommonModel extends Model
{
    protected $guarded = [];
    use SoftDeletes;
}