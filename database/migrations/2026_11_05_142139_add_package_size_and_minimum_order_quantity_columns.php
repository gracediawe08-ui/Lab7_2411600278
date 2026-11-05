<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('package_size')
                ->default(1);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedInteger('minimum_order_quantity')
                ->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('package_size');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('minimum_order_quantity');
        });
    }
};