<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVersion101 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('setting_generate_columns', function (Blueprint $table) {
            if (! Schema::hasColumn('setting_generate_columns', 'is_sort')) {
                $table->addColumn('smallInteger', 'is_sort')
                    ->comment('1 不排序 2 排序字段')
                    ->default(1)
                    ->after('is_query')
                    ->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_generate_columns', function (Blueprint $table) {
            $table->dropColumn(['is_sort']);
        });
    }
}
