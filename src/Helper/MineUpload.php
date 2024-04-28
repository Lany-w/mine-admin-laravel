<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/26 12:59
 */

namespace Lany\MineAdmin\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lany\MineAdmin\Events\UploadAfter;
use Lany\MineAdmin\Exceptions\NormalStatusException;

class MineUpload
{

    /**
     * 上传文件.
     */
    public function upload(UploadedFile $uploadedFile, array $config = []): array
    {
        return $this->handleUpload($uploadedFile, $config);
    }

    /**
     * 处理上传.
     */
    protected function handleUpload(UploadedFile $uploadedFile, array $config): array
    {
        $tmpFile = $uploadedFile->getPath() . '/' . $uploadedFile->getFilename();
        $path = $this->getPath($config['path'] ?? null, $this->getStorageMode() != 1);
        $filename = $this->getNewName() . '.' . Str::lower($uploadedFile->getClientOriginalExtension());

        try {
            Storage::disk('public')->putFileAs($path, $uploadedFile, $filename);
        } catch (\Exception $e) {
            throw new NormalStatusException((string) $e->getMessage(), 500);
        }

        $fileInfo = [
            'storage_mode' => $this->getStorageMode(),
            'origin_name' => $uploadedFile->getClientOriginalName(),
            'object_name' => $filename,
            'mime_type' => $uploadedFile->getMimeType(),
            'storage_path' => $path,
            'hash' => md5_file($tmpFile),
            'suffix' => Str::lower($uploadedFile->getClientOriginalExtension()),
            'size_byte' => $uploadedFile->getSize(),
            'size_info' => format_size($uploadedFile->getSize() * 1024),
            'url' => $this->assembleUrl($config['path'] ?? null, $filename),
        ];

        UploadAfter::dispatch($fileInfo);

        return $fileInfo;
    }

    /**
     * 组装url.
     */
    public function assembleUrl(?string $path, string $filename): string
    {
        return $this->getPath($path, $this->getStorageMode() != 1) . '/' . $filename;
    }

    public function getStorageMode(): int|string
    {
        return app('SettingConfigService')->getConfigByKey('upload_mode')['value'] ?? 1;
    }


    public function getPath(?string $path = null, bool $isContainRoot = false): string
    {
        $uploadfile = $isContainRoot ? '/' . env('UPLOAD_PATH', 'uploadfile') . '/' : '';
        return empty($path) ? $uploadfile . date('Ymd') : $uploadfile . $path;
    }

    /**
     * 获取编码后的文件名.
     */
    public function getNewName(): string
    {
        $snowflake = app('Kra8\Snowflake\Snowflake');
        return (string)$snowflake->next();
    }
}