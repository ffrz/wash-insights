<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return inertia('admin/product/Index');
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
        $item = $id ? Product::findOrFail($id) : new Product(['date' => date('Y-m-d')]);
        return inertia('admin/product/Editor', [
            'data' => $item,
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
        $fields = ['name', 'description'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new Product();
            $message = 'product-created';
        } else {
            $item = Product::findOrFail($request->post('id', 0));
            $message = 'product-updated';
        }

        $data = $request->only($fields);
        $data['description'] = $data['description'] ?? '';

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
