<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:29
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lany\MineAdmin\Traits\HasDateTimeFormatter;
use Lany\MineAdmin\Traits\MineTrait;
use Lany\MineAdmin\Traits\UserDataScope;

class MineModel extends Model
{
    use HasDateTimeFormatter, UserDataScope, MineTrait;
    public const ENABLE = 1;
    public const DISABLE = 2;
    protected $guarded = [];


}