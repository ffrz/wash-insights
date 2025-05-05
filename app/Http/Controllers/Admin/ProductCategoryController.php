<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return inertia('admin/product-category/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'date');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = ProductCategory::query();

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
        $item = ProductCategory::findOrFail($id);
        $item->id = null;
        return inertia('admin/product-category/Editor', [
            'data' => $item
        ]);
    }

    public function editor($id = 0)
    {
        allowed_roles([User::Role_Admin]);
        $item = $id ? ProductCategory::findOrFail($id) : new ProductCategory(['date' => date('Y-m-d')]);
        return inertia('admin/product-category/Editor', [
            'data' => $item,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('product_categories', 'name')->ignore($request->id), // agar saat update tidak dianggap duplikat sendiri
            ],
            'description' => 'nullable|max:1000',
        ];

        $item = null;
        $message = '';
        $fields = ['name', 'description'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new ProductCategory();
            $message = 'product-category-created';
        } else {
            $item = ProductCategory::findOrFail($request->post('id', 0));
            $message = 'product-category-updated';
        }

        $data = $request->only($fields);
        $data['description'] = $data['description'] ?? '';

        $item->fill($data);
        $item->save();

        return redirect(route('admin.product-category.index'))
            ->with('success', __("messages.$message", ['name' => $item->name]));
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = ProductCategory::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => __('messages.product-category-deleted', ['name' => $item->name])
        ]);
    }
}
