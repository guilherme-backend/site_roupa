<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('has_sizes')->default(true)->after('is_active');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('main_image')->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('has_sizes');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('main_image');
        });
    }
};
