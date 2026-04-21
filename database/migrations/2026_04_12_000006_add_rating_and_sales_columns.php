<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->decimal('rating_avg', 3, 2)->default(0)->after('offer_price');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_avg');
            $table->unsignedInteger('sales_count')->default(0)->after('rating_count');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('seller_rating_avg', 3, 2)->default(0)->after('avatar');
            $table->unsignedInteger('seller_rating_count')->default(0)->after('seller_rating_avg');
            $table->unsignedInteger('seller_sales_count')->default(0)->after('seller_rating_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['seller_rating_avg', 'seller_rating_count', 'seller_sales_count']);
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['rating_avg', 'rating_count', 'sales_count']);
        });
    }
};
