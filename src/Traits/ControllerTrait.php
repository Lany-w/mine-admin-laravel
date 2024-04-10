<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 16:34
 */
namespace Lany\MineAdmin\Traits;
use Illuminate\Support\Str;

trait ControllerTrait
{
    public function success(null|array|object|string $msgOrData = '', array|object $data = [], int $code = 200): \Illuminate\Http\JsonResponse
    {

        $format = [
            'requestId' => Str::uuid(),
            'path' => url()->current(),
            'success' => true,
            'message' => 'success',
            'code' => $code,
            'data' => $data,
        ];
        if (is_string($msgOrData) || is_null($msgOrData)) {
            $format['message'] = $msgOrData;
        }
        if (is_array($msgOrData) || is_object($msgOrData)) {
            $format['data'] = $msgOrData;
        }

        return response()->json($format, 200, ['content-type' => 'application/json; charset=utf-8']);
    }


    public function error(string $message = '', int $code = 500, array $data = []): \Illuminate\Http\JsonResponse
    {
        $format = [
            'requestId' => Str::uuid(),
            'path' => url()->current(),
            'success' => false,
            'message' => $message ?: 'fail',
            'code' => $code,
            'data' => $data,
        ];
        if (! empty($data)) {
            $format['data'] = $data;
        }

        return response()->json($format, '200', ['content-type' => 'application/json; charset=utf-8']);
    }

}