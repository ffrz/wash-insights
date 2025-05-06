<?php

use App\Models\Product;
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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->morphs('ref');
            $table->morphs('ref_detail');

            $table->decimal('quantity', 10, 2)->nullable()->default(0); 
            $table->string('uom', 20)->default('');

            $table->decimal('cost', 10, 2)->nullable()->default(0);
            $table->decimal('price', 10, 2)->nullable()->default(0);

            $table->decimal('subtotal_cost', 10, 2)->nullable()->default(0);
            $table->decimal('subtotal_price', 10, 2)->nullable()->default(0);

            $table->text('notes')->nullable()->default(null);

            $table->datetime('created_datetime')->nullable()->default(null);
            $table->unsignedBigInteger('created_by_uid')->nullable()->default(null);

            $table->foreign('created_by_uid')->references('id')->on('users')->onDelete('set null');

            $table->index('ref_id');
            $table->index('ref_detail_id');
            $table->index('created_by_uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
