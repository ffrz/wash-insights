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

            $table->unsignedBigInteger('product_id');
            $table->string('product_name', 100)->default(''); // untuk historical

            $table->unsignedBigInteger('ref_id')->nullable()->default(null);
            $table->string('ref_type', 20)->nullable()->default(null);

            $table->decimal('quantity', 10, 2)->nullable()->default(0); 
            $table->string('uom', 20)->default('');

            $table->datetime('created_datetime')->nullable()->default(null);
            $table->unsignedBigInteger('created_by_uid')->nullable()->default(null);

            // perlu dipertimbangkan lagi untuk efisiensi, bisa saja ini dicatat di masing masing tabel
            // seperti sales_order_detail, purchase_order_detail, stock_adjustment_detail, dst
            // $table->morphs('ref_detail');
            // $table->decimal('cost', 10, 2)->nullable()->default(0);
            // $table->decimal('price', 10, 2)->nullable()->default(0);

            // $table->decimal('subtotal_cost', 10, 2)->nullable()->default(0);
            // $table->decimal('subtotal_price', 10, 2)->nullable()->default(0);

            // $table->text('notes')->nullable()->default(null);

            $table->foreign('created_by_uid')->references('id')->on('users')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->index(['ref_id', 'ref_type']);
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
