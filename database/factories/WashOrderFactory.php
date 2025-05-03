<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Models\WashOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WashOrderFactory extends Factory
{
    protected $vehicles = [
        'Toyota Avanza',
        'Honda Jazz',
        'Suzuki Ertiga',
        'Daihatsu Xenia',
        'Mitsubishi Xpander',
        'Nissan Livina',
        'Toyota Innova',
        'Honda CR-V',
        'Daihatsu Terios',
        'Suzuki XL7',
        'Toyota Fortuner',
        'Honda HR-V',
        'Mitsubishi Pajero',
        'Nissan X-Trail',
        'Toyota Rush',
        'Honda Mobilio',
        'Daihatsu Ayla',
        'Suzuki Karimun',
        'Toyota Agya',
        'Honda Brio',
        'Daihatsu Sigra',
        'Suzuki Baleno',
        'Toyota Yaris',
        'Honda City',
        'Daihatsu Sirion',
        'Suzuki Swift',
        'Toyota Corolla',
        'Honda Accord',
        'Daihatsu Luxio',
        'Suzuki APV',
        'Toyota Hilux',
        'Honda CR-Z',
        'Daihatsu Gran Max',
        'Suzuki Carry',
        'Toyota Land Cruiser',
    ];

    protected $vehicle_plate_prefixes = [
        'E', 'D', 'B', 'Z', 'F', 'G', 'A'
    ];

    protected $model = WashOrder::class;

    public function definition(): array
    {
        $customer = Customer::inRandomOrder()->first();
        $created_by = User::inRandomOrder()->first();

        $order_created_at = Carbon::instance($this->faker->dateTimeThisMonth);
        
        $work_started_at = null;
        $work_completed_at = null;
        $order_closed_at = null;

        $service_status = WashOrder::ServiceStatus_NotStarted;
        $order_status = WashOrder::OrderStatus_Pending;
        $payment_status = $this->faker->randomElement([
            WashOrder::PaymentStatus_Unpaid,
            WashOrder::PaymentStatus_Paid,
        ]);

        // Atur status dan waktu sesuai urutan proses
        if ($this->faker->boolean(80)) {
            $service_status = WashOrder::ServiceStatus_InProgress;
            $order_status = WashOrder::OrderStatus_Confirmed;
            $work_started_at = $order_created_at->copy()->addHours(rand(1, 5));
        }

        if ($service_status === WashOrder::ServiceStatus_InProgress && $this->faker->boolean(70)) {
            $service_status = WashOrder::ServiceStatus_Finished;
            $work_completed_at = $work_started_at->copy()->addHours(rand(1, 3));
        }

        if ($service_status === WashOrder::ServiceStatus_Finished && $payment_status === WashOrder::PaymentStatus_Paid) {
            $order_status = WashOrder::OrderStatus_Completed;
            $order_closed_at = $work_completed_at->copy()->addHours(rand(1, 2));
        }

        $total_price = $this->faker->numberBetween(25, 150) * 1000;
        $vehicle_plate = strtoupper($this->faker->bothify($this->faker->randomElement($this->vehicle_plate_prefixes) . ' #### ??'));

        return [
            'customer_id' => $customer?->id,
            'customer_name' => $customer?->name ?? $this->faker->name,
            'customer_phone' => $customer?->phone ?? $this->faker->phoneNumber,
            'customer_address' => $customer?->address ?? $this->faker->address,

            'vehicle_plate_number' => $vehicle_plate,
            'vehicle_description' => $this->faker->randomElement($this->vehicles),

            'order_created_at' => $order_created_at,
            'work_started_at' => $work_started_at,
            'work_completed_at' => $work_completed_at,
            'order_closed_at' => $order_closed_at,

            'order_status' => $order_status,
            'service_status' => $service_status,
            'payment_status' => $payment_status,

            'total_price' => $total_price,
            'notes' => $this->faker->optional()->paragraph,

            'created_datetime' => now(),
            'updated_datetime' => now(),
            'created_by_uid' => $created_by?->id,
            'updated_by_uid' => $created_by?->id,
        ];
    }
}
