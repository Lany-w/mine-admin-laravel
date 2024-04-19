<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 16:58
 */

namespace Lany\MineAdmin\Traits;
trait HasDateTimeFormatter
{
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}