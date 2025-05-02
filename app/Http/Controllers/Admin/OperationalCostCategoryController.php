<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationalCost;
use App\Models\OperationalCostCategory;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationalCostCategoryController extends Controller
{
    public function index()
    {
        return inertia('admin/operational-cost-category/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'date');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = OperationalCostCategory::query();
        $q->where('company_id', Auth::user()->company_id);

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
        $item = OperationalCostCategory::findOrFail($id);
        $item->id = null;
        return inertia('admin/operational-cost-category/Editor', [
            'data' => $item
        ]);
    }

    public function editor($id = 0)
    {
        allowed_roles([User::Role_Admin]);
        $item = $id ? OperationalCostCategory::findOrFail($id) : new OperationalCostCategory(['date' => date('Y-m-d')]);
        return inertia('admin/operational-cost-category/Editor', [
            'data' => $item,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'notes' => 'nullable|max:1000',
        ];

        $item = null;
        $message = '';
        $fields = ['name', 'notes'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new OperationalCostCategory();
            $item->company_id = Auth::user()->company_id;
            $message = 'operational-cost-category-created';
        } else {
            $item = OperationalCostCategory::findOrFail($request->post('id', 0));
            $message = 'operational-cost-category-updated';
        }

        $data = $request->only($fields);
        $data['notes'] = $data['notes'] ?? '';

        $item->fill($data);
        $item->save();

        return redirect(route('admin.operational-cost-category.index'))
            ->with('success', __("messages.$message", ['name' => $item->name]));
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = OperationalCostCategory::findOrFail($id);
        if ($item->company_id != Auth::user()->company_id) {
            return response()->json([
                'message' => __('messages.cant-delete-item-with-different-company')
            ], 403);
        }
        $item->delete();

        return response()->json([
            'message' => __('messages.operational-cost-category-deleted', ['name' => $item->name])
        ]);
    }
}
