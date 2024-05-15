<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 16:14
 */
namespace Lany\MineAdmin\Admin\System\Dto;

use Lany\MineAdmin\Helper\Annotation\AbstractAnnotation;
use Lany\MineAdmin\Helper\Annotation\ExcelProperty;
use Lany\MineAdmin\Helper\Annotation\ExcelData;
use Lany\MineAdmin\Interfaces\MineModelExcel;

#[ExcelData]
class UserDto implements MineModelExcel
{
    #[ExcelProperty(value: '用户名', index: 0)]
    public string $username;

    #[ExcelProperty(value: '密码', index: 3)]
    public string $password;

    #[ExcelProperty(value: '昵称', index: 1)]
    public string $nickname;

    #[ExcelProperty(value: '手机', index: 2)]
    public string $phone;

    #[ExcelProperty(value: '状态', index: 4, dictName: 'data_status')]
    public string $status;
}