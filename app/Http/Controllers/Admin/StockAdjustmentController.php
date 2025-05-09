<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentDetail;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        // $item = $this->_findOder($id);

        // return inertia('admin/stock-adjustment/Detail', [
        //     'data' => $item
        // ]);
    }

    public function create(Request $request)
    {
        if ($request->method() == 'POST') {
            $product_ids = $request->post('product_ids', []);
            $products = Product::whereIn('id', $product_ids)->get()->keyBy('id');

            DB::beginTransaction();
            $item = new StockAdjustment([
                'datetime' => $request->post('datetime', date('Y-m-d H:i:s')),
                'status' => StockAdjustment::Status_Draft,
                'type' => $request->post('type', StockAdjustment::Type_StockCorrection),
                'notes' => $request->post('notes', ''),
                'total_cost' => 0,
                'total_price' => 0,
            ]);
            $item->save();

            foreach ($product_ids as $product_id) {
                $product = $products[$product_id];
                $detail = new StockAdjustmentDetail([
                    'parent_id' => $item->id,
                    'product_id' => $product_id,
                    'product_name' => $product->name,
                    'old_quantity' => $product->stock,
                    'new_quantity' => $product->stock,
                    'balance' => 0,
                    'uom' => $product->uom,
                    'cost' => $product->cost,
                    'price' => $product->price,
                ]);
                $detail->save();
            }

            DB::commit();

            return redirect(route('admin.stock-adjustment.editor', [
                'id' => $item->id
            ]))->with([
                'message' => __('Penyesuaian stok dibuat', ['id' => $item->id])
            ]);
        }

        return inertia('admin/stock-adjustment/Create', [
            'products' => Product::with(['category'])
                ->where('type', Product::Type_Stocked)
                ->where('active', 1)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'type', 'stock', 'uom', 'cost', 'price']),
        ]);
    }

    public function editor($id)
    {
        $item = StockAdjustment::findOrFail($id);

        $details = DB::table('stock_adjustment_details')
            ->join('products', 'stock_adjustment_details.product_id', '=', 'products.id')
            ->where('stock_adjustment_details.parent_id', $id)
            ->orderBy('stock_adjustment_details.id', 'asc')
            ->select(
                'stock_adjustment_details.id',
                'stock_adjustment_details.new_quantity',
                'stock_adjustment_details.notes',
                'products.id as product_id',
                'products.name as product_name',
                'products.stock as old_quantity',
                'products.uom',
            )
            ->get();

        return inertia('admin/stock-adjustment/Editor', [
            'item' => $item,
            'details' => $details
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'datetime' => ['required', 'date'], // atau gunakan: 'date_format:Y-m-d H:i:s'
            'type' => [
                'required',
                Rule::in(array_keys(StockAdjustment::Types)),
            ],
            'notes' => 'nullable|string|max:1000',
        ];
        $validated = $request->validate($rules);

        $item = StockAdjustment::findOrFail($request->id);
        $item->fill($validated);

        $details = $request->post('details', []);

        DB::beginTransaction();
        foreach ($details as $d) {
            $detail = StockAdjustmentDetail::find($d['id']);
            $detail->new_quantity = $d['new_quantity'];
            $detail->balance = $detail->new_quantity - $detail->old_quantity;
            $detail->subtotal_cost = $detail->balance * $detail->cost;
            $detail->subtotal_price = $detail->balance * $detail->price;
            $detail->save();

            if ($request->post('action') === 'close') {
                $stockMovement = new StockMovement([
                    'product_id' => $detail->product_id,
                    'ref_id' => $detail->id,
                    'ref_type' => StockMovement::RefType_StockAdjustment,
                    'quantity' => $detail->balance,
                ]);
                $stockMovement->save();
            }
        }

        if ($request->post('action') === 'cancel') {
            $item->status = StockAdjustment::Status_Cancelled;
        } else if ($request->post('action') === 'close') {
            $item->status = StockAdjustment::Status_Closed;
        }

        $item->save();
        DB::commit();

        return redirect(route('admin.stock-adjustment.editor', [
            'id' => $item->id
        ]),)->with([
            'message' => 'Berhasil Disimpan'
        ]);
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = StockAdjustment::findOrFail($id);

        // TODO:
        // jika statusnya selesai, maka stock akan dikembalikan
        // yaitu menjumlahkan stok aktual dengan stok yang dikurangi atau ditambah
        // pada penyesuaian stok ini
        // hapus juga semua data di stock_movements

        if ($item->status == StockAdjustment::Status_Closed) {
            DB::beginTransaction();

            $details = StockAdjustmentDetail::where('parent_id', $item->id)->get()->byKey('product_id');

            $products = Product::whereIn('id', array_keys($details))->get();

            foreach ($products as $product) {
                $product->stock += $details[$product->id]->quantity;
            }

            // foreach ($details as $detail) {
            //     DB::query('DELETE FROM stock_movements where ref_type=? and ref_id=?', [
            //         StockMovement::RefType_StockAdjustment,
            //         $detail->id,
            //     ]);
            // }

            // stock movement history juga perlu dihapus dong!
            // foreach ($details as $detail) {

            //     $detail->delete();
            // }
            $item->delete();

            DB::commit();
        }

        return response()->json([
            'message' => __('messages.stock-adjustment-deleted', ['id' => $item->id])
        ]);
    }
}
