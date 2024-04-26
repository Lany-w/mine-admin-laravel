<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 16:55
 */

namespace Lany\MineAdmin\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UploadAfter
{
    use Dispatchable;

    public function __construct(
        public array $fileInfo,
    )
    {
    }
}