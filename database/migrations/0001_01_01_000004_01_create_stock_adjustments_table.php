<?php

use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();

            $table->datetime('datetime')->nullable()->default(null);
            $table->enum('status', array_keys(StockAdjustment::Statuses))->default(StockAdjustment::Status_Draft);
            $table->enum('type', array_keys(StockAdjustment::Types))->default(StockAdjustment::Type_StockCorrection);
            $table->text('notes')->nullable()->default(null);
            $table->decimal('total_cost', 15, 4)->nullable()->default(null);
            $table->decimal('total_price', 15, 4)->nullable()->default(null);

            $table->datetime('created_datetime')->nullable()->default(null);
            $table->datetime('updated_datetime')->nullable()->default(null);
            $table->unsignedBigInteger('created_by_uid')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by_uid')->nullable()->default(null);
            $table->foreign('created_by_uid')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_uid')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
