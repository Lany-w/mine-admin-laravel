<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateSystemUserPostTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_user_post', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('用户与岗位关联表');
            $table->addColumn('bigInteger', 'user_id', ['unsigned' => true, 'comment' => '用户主键']);
            $table->addColumn('bigInteger', 'post_id', ['unsigned' => true, 'comment' => '岗位主键']);
            $table->primary(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_user_post');
    }
}
