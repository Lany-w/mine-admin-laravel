<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/29 16:46
 */

namespace Lany\MineAdmin\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OperationLog
{
    use Dispatchable;


    public function __construct(
        public $response
    )
    {

    }
}