<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\StockAdjustment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    //
    public function index()
    {
        return inertia('admin/stock-adjustment/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = StockAdjustment::with(['createdBy:id,username,name', 'updatedBy:id,username,name']);
        $q->orderBy($orderBy, $orderType);

        if (!empty($filter['status']) && $filter['status'] != 'all') {
            $q->where('status', '=', $filter['status']);
        }

        if (!empty($filter['type']) && $filter['type'] != 'all') {
            $q->where('type', '=', $filter['type']);
        }
        
        if (!empty($filter['search'])) {
            $search = $filter['search'];
            $q->where(function ($q) use ($search) {
                $q->where('notes', 'like', '%' . $search . '%');
            });
        }

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function detail($id = 0)
    {
        $item = $this->_findOder($id);

        return inertia('admin/stock-adjustment/Detail', [
            'data' => $item
        ]);
    }

    public function duplicate($id)
    {
        $item = $this->_findOder($id);
        $item->id = null;
        $item->created_datetime = null;
        $item->created_by_uid = null;
        $item->updated_datetime = null;
        $item->updated_by_uid = null;
        $item->closed_datetime = null;
        $item->closed_by_uid = null;
        $item->order_status = WashOrder::OrderStatus_Open;
        $item->service_status = WashOrder::ServiceStatus_Received;
        $item->repair_status = WashOrder::RepairStatus_NotFinished;
        $item->payment_status = WashOrder::PaymentStatus_Unpaid;
        $item->received_datetime = date('Y-m-d H:i:s');
        $item->checked_datetime = null;
        $item->worked_datetime = null;
        $item->completed_datetime = null;
        $item->picked_datetime = null;

        return $this->_renderEditor($item);
    }

    public function editor($id = 0)
    {
        $item = $this->_findOder($id);
        return $this->_renderEditor($item);
    }

    private function _findOder($id)
    {
        $order = $id ? WashOrder::with([
            // 'createdBy:id,username,name',
            // 'updatedBy:id,username,name',
            // 'closedBy:id,username,name',
        ])->findOrFail($id) : new WashOrder([
            'received_datetime' => date('Y-m-d H:i:s'),
            'order_status' => WashOrder::OrderStatus_Confirmed,
            'service_status' => WashOrder::ServiceStatus_NotStarted,
            'payment_status' => WashOrder::PaymentStatus_Unpaid,
        ]);
        // $order->details = [];

        $itemDetail = WashOrderDetail::where('order_id', $order->id)->get();
        
        $detailId = 1;
        foreach ($itemDetail as $detail) {
            // $order->details[$detailId] = $detail;
            $order->{'service_' . $detailId} = $detail->service_id;
            $detailId++;
        }
        return $order;
    }

    private function _renderEditor($item)
    {
        $customers = Customer::get(['id', 'name', 'phone', 'address']);

        return inertia('admin/stock-adjustment/Editor', [
            'data' => $item,
            'vehicles'  => WashOrder::get(['vehicle_description'])->pluck('vehicle_description')->unique()->values(),
            'services'  => WashService::get(['id', 'name', 'price']),
            'operators' => User::where('role', User::Role_Washer)->get(['id', 'name']),
            'customers' => $customers,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'customer_name' => 'required|max:255',
            'customer_phone' => 'required|max:100',
            'address' => 'nullable|max:1000',

            'vehicle_plate_number' => 'required',
            'vehicle_description' => 'required',
        ];
        $item = null;
        $fields = [
            'customer_id',
            'customer_name',
            'customer_phone',
            'customer_address',
            'vehicle_plate_number',
            'vehicle_description',
            'order_status',
            'service_status',
            'payment_status',
            'total_price',
            'notes'
        ];

        $request->validate($rules);

        $data = $request->only($fields);
        $data['notes'] = $data['notes'] ?? '';
        $data['total_price'] = $data['total_price'] ?? 0;

        if (!$request->id) {
            $item = new WashOrder();
        } else {
            $item = WashOrder::findOrFail($request->post('id', 0));
        }

        DB::transaction(function () use ($data, $item, $request) {
            if (!$data['customer_id']) {
                $customer = Customer::create([
                    'name' => $request->input('customer_name'),
                    'phone' => $request->input('customer_phone'),
                    'address' => $request->input('customer_address'),
                ]);
                $data['customer_id'] = $customer->id;
            }

            $item->fill($data);
            $item->save();

            for ($i = 1; $i <= 5; $i++) {
                $serviceId = $request->input('service_' . $i);
                if ($serviceId) {
                    $orderDetail = new WashOrderDetail([
                        'id' => $i,
                        'order_id' => $item->id,
                        'service_id' => $serviceId,
                        'operator_id' => $request->input('operator_' . $i),
                        'price' => WashService::where('id', $serviceId)->value('price'),
                    ]);
                    $orderDetail->save();
                }
            }
        });

        return redirect(route('admin.stock-adjustment.index'))->with([
            'message' => __('messages.stock-adjustment-saved', ['id' => $item->id])
        ]);
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = WashOrder::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => __('messages.stock-adjustment-deleted', ['id' => $item->id])
        ]);
    }
}
