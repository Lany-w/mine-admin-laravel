<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/2 17:15
 */
namespace Lany\MineAdmin\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lany\MineAdmin\Events\UserLoginBefore;
use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Exceptions\UserBanException;
use Lany\MineAdmin\Helper\MineCode;
use Lany\MineAdmin\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends MineController
{
    /**
     * Notes:获取每日的必应背景图.
     * User: Lany
     * DateTime: 2024/4/7 17:01
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBingBackgroundImage(): JsonResponse
    {
        try {
            $response = file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
            $content = json_decode($response);
            if (! empty($content?->images[0]?->url)) {
                return $this->success(['url' => 'https://cn.bing.com' . $content?->images[0]?->url]);
            }
            throw new \Exception();
        } catch (\Exception $e) {
            return $this->error('获取必应背景失败');
        }
    }

    /**
     * Notes:登录
     * User: Lany
     * DateTime: 2024/4/11 13:13
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            UserLoginBefore::dispatch($request->input());
            return $this->success(['token' => app('SystemUserService')->login()]);
        } catch (\Exception $e){
            if ($e instanceof ModelNotFoundException) {
                throw new NormalStatusException(t('jwt.login_error'), MineCode::NO_USER);
            }
            if ($e instanceof NormalStatusException) {
                throw new NormalStatusException(t('jwt.login_error'), MineCode::NO_USER);
            }
            if ($e instanceof UserBanException) {
                throw new NormalStatusException(t('jwt.user_ban'), MineCode::USER_BAN);
            }
            throw new NormalStatusException(t('jwt.unknown_error'));
        }

    }

    /**
     * Notes:刷新token
     * User: Lany
     * DateTime: 2024/4/11 13:13
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->success(['token' =>$this->guard()->refresh()]);
    }

    /**
     * Notes:退出登录
     * User: Lany
     * DateTime: 2024/4/11 13:13
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return $this->success();
    }

    /**
     * Notes:用户信息
     * User: Lany
     * DateTime: 2024/4/11 13:13
     * @return JsonResponse|MineException
     */
    public function getInfo(): JsonResponse|MineException
    {
        return $this->success(app('SystemUserService')->getInfo());
    }
}