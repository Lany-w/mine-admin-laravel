<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:24
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingConfigGroup extends MineModel
{
    protected $table = 'setting_config_group';

    public function configs(): HasMany
    {
        return $this->hasMany(SettingConfig::class, 'group_id', 'id');
    }
}