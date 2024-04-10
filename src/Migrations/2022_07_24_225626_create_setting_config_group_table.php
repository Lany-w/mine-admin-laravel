<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingConfigGroupTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setting_config_group', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('参数配置分组表');
            $table->bigIncrements('id')->comment('主键');
            $table->addColumn('string', 'name', ['length' => 32, 'comment' => '配置组名称']);
            $table->addColumn('string', 'code', ['length' => 64, 'comment' => '配置组标识']);
            $table->addColumn('bigInteger', 'created_by', ['comment' => '创建者'])->nullable();
            $table->addColumn('bigInteger', 'updated_by', ['comment' => '更新者'])->nullable();
            $table->addColumn('timestamp', 'created_at', ['precision' => 0, 'comment' => '创建时间'])->nullable();
            $table->addColumn('timestamp', 'updated_at', ['precision' => 0, 'comment' => '更新时间'])->nullable();
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_config_group');
    }
}
