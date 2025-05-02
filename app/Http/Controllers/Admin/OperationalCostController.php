<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationalCost;
use App\Models\OperationalCostCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationalCostController extends Controller
{
    protected function _categories()
    {
        return OperationalCostCategory::where('company_id', Auth::user()->company_id)->get();
    }

    public function index()
    {
        $categories = OperationalCostCategory::where('company_id', Auth::user()->company_id)->get();
        return inertia('admin/operational-cost/Index', [
            'categories' => $this->_categories(),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'date');
        $orderType = $request->get('order_type', 'desc');
        $filter = $request->get('filter', []);

        $q = OperationalCost::with('category');
        $q->where('company_id', Auth::user()->company_id);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('description', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('notes', 'like', '%' . $filter['search'] . '%');
            });
        }

        if (!empty($filter['category_id'])) {
            if ($filter['category_id'] === 'null') {
                $q->whereNull('category_id');
            }
            else if ($filter['category_id'] !== 'all') {
                $q->where('category_id', '=', $filter['category_id']);
            }
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        allowed_roles([User::Role_Admin]);
        $item = OperationalCost::findOrFail($id);
        $item->id = null;
        return inertia('admin/operational-cost/Editor', [
            'data' => $item,
            'categories' => $this->_categories(),
        ]);
    }

    public function editor($id = 0)
    {
        allowed_roles([User::Role_Admin]);
        $item = $id ? OperationalCost::findOrFail($id) : new OperationalCost(['date' => date('Y-m-d')]);
        return inertia('admin/operational-cost/Editor', [
            'data' => $item,
            'categories' => $this->_categories(),
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'date' => 'required|date',
            'category_id' => 'nullable',
            'description' => 'required|max:255',
            'amount' => 'required|numeric|gt:0',
            'notes' => 'nullable|max:1000',
        ];

        $item = null;
        $message = '';
        $fields = ['date', 'description', 'amount', 'notes', 'category_id'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new OperationalCost();
            $item->company_id = Auth::user()->company_id;
            $message = 'operational-cost-created';
        } else {
            $item = OperationalCost::findOrFail($request->post('id', 0));
            $message = 'operational-cost-updated';
        }

        $data = $request->only($fields);
        $data['notes'] = $data['notes'] ?? '';

        $item->fill($data);
        $item->save();

        return redirect(route('admin.operational-cost.index'))
            ->with('success', __("messages.$message", ['description' => $item->description]));
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = OperationalCost::findOrFail($id);
        if ($item->company_id != Auth::user()->company_id) {
            return response()->json([
                'message' => __('messages.cant-delete-item-with-different-company')
            ], 403);
        }
        $item->delete();

        return response()->json([
            'message' => __('messages.operational-cost-deleted', ['description' => $item->description])
        ]);
    }
}
