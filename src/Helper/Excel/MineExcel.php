<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/11 16:54
 */

namespace Lany\MineAdmin\Helper\Excel;

use Lany\MineAdmin\Admin\System\Dto\UserDto;
use Lany\MineAdmin\Helper\Annotation\Handle\ExcelPropertyAnnotation;

class MineExcel
{
    protected array $property;
    /**
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->property = ExcelPropertyAnnotation::getAnnotation(UserDto::class);
    }

    protected function getColumnIndex(int $columnIndex = 0): string
    {
        if ($columnIndex < 26) {
            return chr(65 + $columnIndex);
        }
        if ($columnIndex < 702) {
            return chr(64 + intval($columnIndex / 26)) . chr(65 + $columnIndex % 26);
        }
        return chr(64 + intval(($columnIndex - 26) / 676)) . chr(65 + intval((($columnIndex - 26) % 676) / 26)) . chr(65 + $columnIndex % 26);
    }

    public function downloadExcel($filePath, string $filename)
    {
        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename={$filename}; filename*=UTF-8''" . rawurlencode($filename),
            'Content-Length' => filesize($filePath),
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control' => 'must-revalidate',
            'pragma' => 'public',
        ]);
    }
}