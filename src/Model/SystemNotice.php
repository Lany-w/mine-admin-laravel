<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 12:46
 */

namespace Lany\MineAdmin\Model;

use Lany\MineAdmin\Traits\PageList;

class SystemNotice extends CommonModel
{
    use PageList;
    protected $table = 'system_notice';

}