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

    /**
     * 通过HASH获取文件信息.
     */
    public function getFileInfoByHash(): JsonResponse
    {
        return $this->success($this->service->readByHash($this->request->input('hash', null)) ?? []);
    }

    /**
     * 输出图片、文件.
     */
    public function showFile(string $hash): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return $this->service->responseFile($hash);
    }
}