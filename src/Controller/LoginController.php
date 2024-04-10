<?php
namespace Lany\MineAdmin\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Lany\MineAdmin\Events\UserLoginAfter;
use Lany\MineAdmin\Events\UserLoginBefore;
use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Exceptions\UserBanException;
use Lany\MineAdmin\Helper\MineCode;
use Lany\MineAdmin\Model\SystemUser;
use Lany\MineAdmin\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/2 17:15
 */
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

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            UserLoginBefore::dispatch($request->input());
            $model = new SystemUser();

            $user = $model->checkUserByUsername($request->input('username'));
            if (Hash::check($request->input('password'), $user->password)) {
                if (
                    ($user->status == SystemUser::USER_NORMAL)
                    || ($user->status == SystemUser::USER_BAN && $user->id == 1)
                ) {
                    $token = $this->guard()->login($user);
                    UserLoginAfter::dispatch($user, true);
                    return $this->success(['token' => $token]);
                }

                UserLoginAfter::dispatch($user, -1);
                throw new UserBanException();
            }
            UserLoginAfter::dispatch($user, false);
            throw new NormalStatusException();
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

    public function getInfo()
    {

    }
}