<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/11 16:16
 */

namespace Lany\MineAdmin\Interfaces;

use Lany\MineAdmin\Model\MineModel;

interface ExcelPropertyInterface
{
    public function import(MineModel $model, ?\Closure $closure = null): bool;

    public function export(string $filename, array|\Closure $closure);
}