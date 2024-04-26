<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 15:58
 */

namespace Lany\MineAdmin\Controller;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Requests\UploadImageRequest;
use Lany\MineAdmin\Services\SystemUploadFileService;

class UploadController extends MineController
{
    protected SystemUploadFileService $service;

    /**
     * 上传图片.
     */
    public function uploadImage(UploadImageRequest $request): JsonResponse
    {
        if ($request->validated() && $request->file('image')->isValid()) {
            $data = $this->service->upload(
                $request->file('image'),
                $request->all()
            );
            return empty($data) ? $this->error() : $this->success($data);
        }
        return $this->error(t('system.upload_image_verification_fail'));
    }
}