<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 16:52
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Helper\MineUpload;
use Lany\MineAdmin\Model\SystemUploadFile;

class SystemUploadFileService extends SystemService
{
    public string $model = SystemUploadFile::class;
    protected MineUpload $mineUpload;

    public function __construct(MineUpload $mineUpload)
    {
        $this->mineUpload = $mineUpload;
    }

    /**
     * 上传文件.
     */
    public function upload(UploadedFile $uploadedFile, array $config = []): array
    {
        try {
            $hash = md5_file($uploadedFile->getPath() . '/' . $uploadedFile->getFilename());
            if ($model = app($this->model)->getFileInfoByHash($hash)) {
                return $model->toArray();
            }
        } catch (\Exception $e) {
            throw new NormalStatusException('获取文件Hash失败', 500);
        }


        try {
           $data = $this->mineUpload->upload($uploadedFile, $config);
        } catch (\Exception $e) {
            throw new NormalStatusException((string) $e->getMessage(), 500);
        }
        if (app($this->model)->create($data)) {
            return $data;
        }

        return [];
    }

    /**
     * 通过hash获取文件信息.
     */
    public function readByHash(string $hash, array $columns = ['*']): mixed
    {
        return app($this->model)->getFileInfoByHash($hash, $columns);
    }


    public function responseFile(string $hash)
    {
        $model = $this->readByHash($hash, ['url', 'mime_type']);
        if (! $model) {
            throw new NormalStatusException('文件不存在', 500);
        }

        return response()->file(Storage::disk('public')->path($this->mineUpload->getStorageMode() == '1'
            ? str_replace(env('UPLOAD_PATH', 'uploadfile'), '', $model->url)
            : $model->url));
    }




}