<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_orders', function (Blueprint $table): void {
            $table->string('payment_method', 20)->default('card')->after('status')->index();
        });
    }

    public function down(): void
    {
        Schema::table('listing_orders', function (Blueprint $table): void {
            $table->dropColumn('payment_method');
        });
    }
};