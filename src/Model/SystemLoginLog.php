<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 17:24
 */

namespace Lany\MineAdmin\Model;

class SystemLoginLog extends CommonModel
{
    public $timestamps = false;
    public const SUCCESS = 1;
    public const FAIL = 2;
    protected $table = 'system_login_log';
}