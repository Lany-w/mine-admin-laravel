<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 16:52
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Lany\MineAdmin\Events\UploadAfter;
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




}