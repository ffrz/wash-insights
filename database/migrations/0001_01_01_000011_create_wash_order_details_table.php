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
        Schema::create('wash_order_details', function (Blueprint $table) {
            $table->unsignedTinyInteger('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->decimal('price', 8, 2)->nullable()->default(0);
            $table->text('notes')->nullable();

            $table->primary(['order_id', 'id']);

            $table->foreign('order_id')->references('id')->on('wash_orders')->onDelete('cascade');
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('wash_services')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wash_order_details');
    }
};
