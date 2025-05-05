<?php

use App\Models\Product;
use App\Models\WashOrder;
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
        Schema::create('wash_orders', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('customer_id')->nullable()->default(null);
            $table->string('customer_name')->nullable()->default('');
            $table->string('customer_phone')->nullable()->default('');
            $table->string('customer_address')->nullable()->default('');

            $table->string('vehicle_plate_number')->nullable()->default('');
            $table->string('vehicle_description')->nullable()->default('');

            $table->datetime('order_created_at')->nullable();     // saat input order, bisa default now()
            $table->datetime('work_started_at')->nullable();      // saat operator mulai kerja
            $table->datetime('work_completed_at')->nullable();    // saat pekerjaan selesai
            $table->datetime('order_closed_at')->nullable();      // saat order dianggap selesai total

            $table->enum('order_status', array_keys(WashOrder::OrderStatuses))->default(WashOrder::OrderStatus_Pending);
            $table->enum('service_status', array_keys(WashOrder::ServiceStatuses))->default(WashOrder::ServiceStatus_NotStarted);
            $table->enum('payment_status', array_keys(WashOrder::PaymentStatuses))->default(WashOrder::PaymentStatus_Unpaid);
            // $table->enum('delivery_status', WashOrder::DeliveryStatuses)->default(WashOrder::DeliveryStatus_NotRequired);

            $table->decimal('total_price', 10, 2)->nullable()->default(0);
            $table->text('notes')->nullable()->default(null);

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
        Schema::dropIfExists('wash_orders');
    }
};
