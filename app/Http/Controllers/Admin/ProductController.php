<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return inertia('admin/product/Index', [
            'categories' => ProductCategory::all(['id', 'name']),
            'suppliers' => Supplier::all(['id', 'name', 'phone']),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'date');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = Product::query();

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
            });
        }

        if (!empty($filter['type']) && $filter['type'] != 'all') {
            $q->where('type', '=', $filter['type']);
            if ($filter['type'] == Product::Type_Stocked) {
                if (!empty($filter['stock_status']) && $filter['stock_status'] != 'all') {
                    if ($filter['stock_status'] == 'low') {
                        $q->whereColumn('stock', '<', 'min_stock');
                        $q->where('stock', '!=', 0);
                    } elseif ($filter['stock_status'] == 'out') {
                        $q->where('stock', '=', 0);
                    } elseif ($filter['stock_status'] == 'over') {
                        $q->whereColumn('stock', '>', 'max_stock');
                    } elseif ($filter['stock_status'] == 'ready') {
                        $q->where('stock', '>', 0);
                    }
                }
            }
        }

        if (!empty($filter['category_id']) && $filter['category_id'] != 'all') {
            $q->where('category_id', '=', $filter['category_id']);
        }

        if (!empty($filter['supplier_id']) && $filter['supplier_id'] != 'all') {
            $q->where('supplier_id', '=', $filter['supplier_id']);
        }

        if (!empty($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $q->where('active', '=', $filter['status'] == 'active' ? true : false);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        allowed_roles([User::Role_Admin]);
        $item = Product::findOrFail($id);
        $item->id = null;
        return inertia('admin/product/Editor', [
            'data' => $item
        ]);
    }

    public function editor($id = 0)
    {
        allowed_roles([User::Role_Admin]);
        $item = $id ? Product::findOrFail($id) : new Product(
            ['active' => 1, 'type' => Product::Type_Stocked]
        );
        return inertia('admin/product/Editor', [
            'data' => $item,
            'categories' => ProductCategory::all(['id', 'name']),
            'suppliers' => Supplier::all(['id', 'name', 'phone']),
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('products', 'name')->ignore($request->id), // agar saat update tidak dianggap duplikat sendiri
            ],
            'description' => 'nullable|max:1000',
        ];

        $item = null;
        $message = '';

        $request->validate($rules);

        if (!$request->id) {
            $item = new Product();
            $message = 'product-created';
        } else {
            $item = Product::findOrFail($request->post('id', 0));
            $message = 'product-updated';
        }

        $data = $request->all();
        $data['category_id'] = $data['category_id'] ?? null;
        $data['supplier_id'] = $data['supplier_id'] ?? '';
        $data['description'] = $data['description'] ?? '';
        $data['notes'] = $data['notes'] ?? '';
        $data['stock'] = $data['stock'] ?? 0;
        $data['min_stock'] = $data['min_stock'] ?? 0;
        $data['max_stock'] = $data['max_stock'] ?? 0;

        $item->fill($data);
        $item->save();

        return redirect(route('admin.product.index'))
            ->with('success', __("messages.$message", ['name' => $item->name]));
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Product::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => __('messages.product-deleted', ['name' => $item->name])
        ]);
    }
}
