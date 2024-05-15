<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 10:54
 */
namespace Lany\MineAdmin\Requests;
class MineRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge(
            $this->callNextFunction('common', __FUNCTION__),
            $this->callNextFunction($this->getAction(), __FUNCTION__)
        );
    }

    public function attributes(): array
    {
        return array_merge(
            $this->callNextFunction('common', __FUNCTION__),
            $this->callNextFunction($this->getAction(), __FUNCTION__)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return array_merge(
            $this->callNextFunction('common', __FUNCTION__),
            $this->callNextFunction($this->getAction(), __FUNCTION__)
        );
    }

    protected function callNextFunction(?string $prefix, string $function): array
    {
        if (is_null($prefix)) {
            return [];
        }
        $callName = $prefix . ucfirst($function);
        return method_exists($this, $callName) ? call_user_func([$this, $callName]) : [];
    }

    protected function getAction(): ?string
    {
        $route = request()->route();
        return $route->getActionMethod();
    }

    /**
     * @deprecated >v1.5.0
     */
    protected function getOperation(): ?string
    {
        $path = explode('/', $this->getFixPath());
        do {
            $operation = array_pop($path);
        } while (is_numeric($operation));

        return $operation;
    }

    /**
     * request->path在单元测试中拿不到，导致MineFormRequest验证失败
     * 取uri中的path, fix.
     * @return null|string
     */
    protected function getFixPath(): string
    {
        return ltrim($this->getRequestUri(), '/');
    }
}