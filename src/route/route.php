<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 17:04
 */

use Lany\MineAdmin\Controller\CommonController;
use Lany\MineAdmin\Controller\DataCenter\DictDataController;
use Lany\MineAdmin\Controller\DataCenter\QueueMessageController;
use Lany\MineAdmin\Controller\DataCenter\DictTypeController;
use Lany\MineAdmin\Controller\DataCenter\AttachmentController;
use Lany\MineAdmin\Controller\DataCenter\DataMaintainController;
use Lany\MineAdmin\Controller\App\SystemAppGroupController;
use Lany\MineAdmin\Controller\App\SystemAppController;
use Lany\MineAdmin\Controller\Api\SystemApiGroupController;
use Lany\MineAdmin\Controller\Api\SystemApiController;
use Lany\MineAdmin\Controller\Monitor\ServerMonitorController;
use Lany\MineAdmin\Controller\Monitor\OnlineUserMonitorController;
use Lany\MineAdmin\Controller\Monitor\CacheMonitorController;
use Lany\MineAdmin\Controller\DataCenter\NoticeController;
use Lany\MineAdmin\Controller\LoginController;
use Lany\MineAdmin\Controller\Permission\DeptController;
use Lany\MineAdmin\Controller\Permission\RoleController;
use Lany\MineAdmin\Controller\Permission\PostController;
use Lany\MineAdmin\Controller\Permission\UserController;
use Lany\MineAdmin\Controller\Permission\MenuController;
use Lany\MineAdmin\Controller\Logs\LogsController;
use Lany\MineAdmin\Controller\Setting\ModuleController;
use Lany\MineAdmin\Controller\Tools\GenerateCodeController;
use Lany\MineAdmin\Controller\Setting\SystemConfigGroupController;
use Lany\MineAdmin\Controller\Setting\SystemConfigController;
use Lany\MineAdmin\Controller\Tools\CrontabController;
use Lany\MineAdmin\Controller\UploadController;
use Lany\MineAdmin\Mine;


Mine::routes();

$attributes = [
    'prefix'     => 'system',
    'middleware' => array_merge(['api', 'mine.auth', 'mine.permission', 'mine.oper.log'], config('mine_admin.route.middleware')),
];
$authController = config('mine_admin.auth.controller', LoginController::class);
app('router')->get('/swagger', function() {
    return 'Under development';
});

app('router')->group($attributes, function ($router) use($authController) {

    app('router')->get('/getBingBackgroundImage', $authController.'@getBingBackgroundImage')->withoutMiddleware(['mine.auth', 'mine.permission']);
    app('router')->post('/login', $authController.'@login')->withoutMiddleware(['mine.auth', 'mine.permission']);
    $router->post('uploadImage', [UploadController::class, 'uploadImage']);
    $router->get('getFileInfoByHash', [UploadController::class, 'getFileInfoByHash']);
    $router->get('showFile/{hash}', [UploadController::class, 'showFile'])->withoutMiddleware(['mine.auth', 'mine.permission']);
    $router->post('/logout', $authController.'@logout');
    $router->get('/getInfo', $authController.'@getInfo');
    /**
     * common
     */
    app('router')->group([
        'prefix' => 'common',
        'controller' => CommonController::class
    ], function ($router) {
        $router->get('/getNoticeList', 'getNoticeList');
        $router->get('/getLoginLogList', 'getLoginLogPageList');
        $router->get('/getOperationLogList', 'getOperLogPageList');
        $router->get('/getResourceList', 'getResourceList');
        $router->get('/getDeptTreeList', 'getDeptTreeList');
        $router->get('/getRoleList', 'getRoleList');
        $router->get('/getPostList', 'getPostList');
        $router->get('/getUserList', 'getUserList');
        $router->post('/getUserInfoByIds', 'getUserInfoByIds');
    });
    /**
     * dataDict
     */
    app('router')->group(['prefix' => 'dataDict', 'controller' => DictDataController::class], function ($router) {
        $router->get('/list', 'list');
    });
    /**
     * queueMessage
     */
    app('router')->group(['prefix' => 'queueMessage', 'controller' => QueueMessageController::class], function ($router) {
        $router->get('/receiveList', 'receiveList');
        $router->get('/sendList', 'sendList');
    });
    /**
     * dept
     */
    app('router')->group(['prefix' => 'dept', 'controller' => DeptController::class], function ($router) {
        $router->get('/index', 'index');
        $router->get('/tree', 'tree');
    });
    /**
     * role
     */
    app('router')->group(['prefix' => 'role', 'controller' => RoleController::class], function ($router) {
        $router->get('/index', 'index');
        $router->get('/list', 'list');
        $router->post('/save', 'save');
    });
    /**
     * post
     */
    app('router')->group(['prefix' => 'post', 'controller' => PostController::class], function ($router) {
        $router->get('/index', 'index');
        $router->get('/list', 'list');
    });
    /**
     * user
     */
    app('router')->group(['prefix' => 'user', 'controller' => UserController::class], function ($router) {
        $router->get('/index', 'index');
        $router->post('/save', 'save');
        $router->delete('/delete', 'delete');
        $router->post('/updateInfo', 'updateInfo');
        $router->post('/clearCache', 'clearCache');
        $router->put('/changeStatus', 'changeStatus');
        $router->post('/modifyPassword', 'modifyPassword');
        $router->post('/downloadTemplate', 'downloadTemplate')->middleware('mine.delete.file');
        $router->post('/import', 'import');
        $router->post('/export', 'export')->middleware('mine.delete.file');
        $router->get('/read/{id}', 'read');
        $router->put('/update/{id}', 'update');
        $router->post('/setHomePage', 'setHomePage');
        $router->put('/initUserPassword', 'initUserPassword');
    });
    /**
     * menu
     */
    app('router')->group(['prefix' => 'menu', 'controller' => MenuController::class], function ($router) {
        $router->get('/index', 'index');
        $router->get('/tree', 'tree');
    });
    /**
     * dictType
     */
    app('router')->group(['prefix' => 'dictType', 'controller' => DictTypeController::class], function ($router) {
        $router->get('/index', 'index');
    });
    /**
     * attachment
     */
    app('router')->group(['prefix' => 'attachment', 'controller' => AttachmentController::class], function ($router) {
        $router->get('/index', 'index');
    });
    /**
     * dataMaintain
     */
    app('router')->group(['prefix' => 'dataMaintain', 'controller' => DataMaintainController::class], function ($router) {
        $router->get('/index', 'index');
    });
    /**
     * notice
     */
    app('router')->group(['prefix' => 'notice', 'controller' => NoticeController::class], function ($router) {
        $router->get('/index', 'index');
    });
    /**
     * appGroup
     */
    app('router')->group(['prefix' => 'appGroup', 'controller' => SystemAppGroupController::class], function ($router) {
        $router->get('/index', 'index');
        $router->get('/list', 'list');
    });
    /**
     * app
     */
    app('router')->group(['prefix' => 'app', 'controller' => SystemAppController::class], function ($router) {
        $router->get('/index', 'index');
    });
    /**
     * apiGroup
     */
    app('router')->group(['prefix' => 'apiGroup', 'controller' => SystemApiGroupController::class], function ($router) {
        $router->get('/index', 'index');
        $router->get('/list', 'list');
    });
    /**
     * api
     */
    app('router')->group(['prefix' => 'api', 'controller' => SystemApiController::class], function ($router) {
        $router->get('/index', 'index');
    });
    /**
     * server
     */
    app('router')->group(['prefix' => 'server', 'controller' => ServerMonitorController::class], function ($router) {
        $router->get('/monitor', 'getServerInfo');
    });
    /**
     * onlineUser
     */
    app('router')->group(['prefix' => 'onlineUser', 'controller' => OnlineUserMonitorController::class], function ($router) {
        $router->get('/index', 'getPageList');
    });
    /**
     * cache
     */
    app('router')->group(['prefix' => 'cache', 'controller' => CacheMonitorController::class], function ($router) {
        $router->get('/monitor', 'getCacheInfo');
    });
    /**
     * logs
     */
    app('router')->group(['prefix' => 'logs', 'controller' => LogsController::class], function ($router) {
        $router->get('/getLoginLogPageList', 'getLoginLogPageList');
        $router->get('/getOperLogPageList', 'getOperLogPageList');
        $router->get('/getApiLogPageList', 'getApiLogPageList');
        $router->get('/getQueueLogPageList', 'getQueueLogPageList');
    });
});

app('router')->group([
    'prefix' => 'setting'
], function($router) {
    $router->get('/module/index', [ModuleController::class, 'index']);
    $router->get('/code/index', [GenerateCodeController::class, 'index']);
    $router->get('/code/getDataSourceList', [GenerateCodeController::class, 'getDataSourceList']);
    $router->get('/crontab/index', [CrontabController::class, 'index']);
    $router->get('/configGroup/index', [SystemConfigGroupController::class, 'index']);
    $router->get('/config/index', [SystemConfigController::class, 'index']);
});