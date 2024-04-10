<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAppApiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_app_api', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('应用和api关联表');
            $table->addColumn('bigInteger', 'app_id', ['unsigned' => true, 'comment' => '应用ID']);
            $table->addColumn('bigInteger', 'api_id', ['unsigned' => true, 'comment' => 'API—ID']);
            $table->primary(['app_id', 'api_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_app_api');
    }
}
