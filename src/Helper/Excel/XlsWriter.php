<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/11 16:13
 */
namespace Lany\MineAdmin\Helper\Excel;

use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Interfaces\ExcelPropertyInterface;
use Lany\MineAdmin\Middlewares\DeleteFileAfterDownload;
use Lany\MineAdmin\Model\MineModel;
use Vtiful\Kernel\Excel;
use Vtiful\Kernel\Format;

class XlsWriter extends MineExcel implements ExcelPropertyInterface
{

    public function import($model, ?\Closure $closure = null): bool
    {
        $request = request();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $tempFileName = 'import_' . time() . '.' . $file->getClientOriginalExtension();
            $tempFilePath = base_path() . '/storage/' . $tempFileName;
            file_put_contents($tempFilePath, $file->getContent());

            $xlsxObject = new Excel(['path' => base_path() . '/storage/']);
            $data = $xlsxObject->openFile($tempFileName)->openSheet()->getSheetData();
            unset($data[0]);

            $importData = [];
            foreach ($data as $item) {
                $tmp = [];
                foreach ($item as $key => $value) {
                    $tmp[$this->property[$key]['name']] = (string) $value;
                }
                $importData[] = $tmp;
            }
            if ($closure instanceof \Closure) {
                return $closure($model, $importData);
            }

            try {
                foreach ($importData as $item) {
                    $model::create($item);
                }
                @unlink($tempFilePath);
            } catch (\Exception $e) {
                @unlink($tempFilePath);
                throw new \Exception($e->getMessage());
            }
            return true;
        }
        return false;
    }

    public function export(string $filename, array|\Closure $closure, ?\Closure $callbackData = null)
    {
        $filename .= '.xlsx';
        is_array($closure) ? $data = &$closure : $data = $closure();
        $aligns = [
            'left' => Format::FORMAT_ALIGN_LEFT,
            'center' => Format::FORMAT_ALIGN_CENTER,
            'right' => Format::FORMAT_ALIGN_RIGHT,
        ];

        $columnName = [];
        $columnField = [];

        foreach ($this->property as $item) {
            $columnName[] = $item['value'];
            $columnField[] = $item['value'];
        }

        $tempFileName = 'export_' . time() . '.xlsx';
        $xlsxObject = new Excel(['path' => base_path() . '/storage/']);
        $fileObject = $xlsxObject->fileName($tempFileName)->header($columnName);
        $columnFormat = new Format($fileObject->getHandle());
        $rowFormat = new Format($fileObject->getHandle());

        $i = 0;
        foreach ($this->property as $index => $item) {
            $fileObject->setColumn(
                sprintf('%s1:%s1', $this->getColumnIndex($i), $this->getColumnIndex($i)),
                $this->property[$index]['width'] ?? mb_strlen($columnName[$i]) * 5,
                $columnFormat->align($this->property[$index]['align'] ? $aligns[$this->property[$index]['align']] : $aligns['left'])
                    ->background($this->property[$index]['bgColor'] ?? Format::COLOR_WHITE)
                    ->border(Format::BORDER_THIN)
                    ->fontColor($this->property[$index]['color'] ?? Format::COLOR_BLACK)
                    ->toResource()
            );
            ++$i;
        }

        // 表头加样式
        $fileObject->setRow(
            sprintf('A1:%s1', $this->getColumnIndex(count($columnField))),
            20,
            $rowFormat->bold()->align(Format::FORMAT_ALIGN_CENTER, Format::FORMAT_ALIGN_VERTICAL_CENTER)
                ->background(0x4AC1FF)->fontColor(Format::COLOR_BLACK)
                ->border(Format::BORDER_THIN)
                ->toResource()
        );

        $exportData = [];
        foreach ($data as $item) {
            $yield = [];
            if ($callbackData) {
                $item = $callbackData($item);
            }
            foreach ($this->property as $property) {
                if (!isset($item[$property['name']])) {
                    $yield[] = '';
                    continue;
                }
                foreach ($item as $name => $value) {
                    if ($property['name'] == $name) {
                        if (! empty($property['dictName'])) {
                            $yield[] = $property['dictName'][$value];
                        } elseif (! empty($property['dictData'])) {
                            $yield[] = $property['dictData'][$value];
                        } elseif (! empty($property['path'])) {
                            $yield[] = data_get($item, $property['path']);
                        } elseif (! empty($this->dictData[$name])) {
                            $yield[] = $this->dictData[$name][$value] ?? '';
                        } else {
                            $yield[] = $value;
                        }
                        break;
                    }
                }
            }
            $exportData[] = $yield;
        }

        $filePath = $fileObject->data($exportData)->output();
        DeleteFileAfterDownload::$filePath = $filePath;
        return $this->downloadExcel($filePath, $filename);

    }
}