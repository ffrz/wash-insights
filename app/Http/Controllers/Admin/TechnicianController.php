<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicianController extends Controller
{
    public function index()
    {
        return inertia('admin/technician/Index');
    }

    public function detail($id)
    {
        return inertia('admin/technician/Detail', [
            'data' => Technician::with('user')->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Technician::query();
        $q->where('company_id', Auth::user()->company_id);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('phone', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('address', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('email', 'like', '%' . $filter['search'] . '%');
            });
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
        $item = Technician::findOrFail($id);
        $item->id = null;
        $item->created_at = null;
        $users = User::where('company_id', Auth::user()->company_id)->get(['id', 'username', 'name']);
        return inertia('admin/technician/Editor', [
            'data' => $item,
            'users' => $users,
        ]);
    }

    public function editor($id = 0)
    {
        allowed_roles([User::Role_Admin]);
        $item = $id ? Technician::findOrFail($id) : new Technician(['active' => true]);
        $users = User::where('company_id', Auth::user()->company_id)->get(['id', 'username', 'name']);
        return inertia('admin/technician/Editor', [
            'data' => $item,
            'users' => $users,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
        ];

        $item = null;
        $message = '';
        $fields = ['name', 'user_id', 'active', 'phone', 'email', 'address'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new Technician();
            $item->company_id = Auth::user()->company_id;
            $message = 'technician-created';
        } else {
            $item = Technician::findOrFail($request->post('id', 0));
            $message = 'technician-updated';
        }

        $data = $request->only($fields);
        $data['phone'] = $data['phone'] ?? '';
        $data['email'] = $data['email'] ?? '';
        $data['address'] = $data['address'] ?? '';
        $data['user_id'] = $data['user_id'] ?? null;

        $item->fill($data);
        $item->save();

        return redirect(route('admin.technician.index'))
            ->with('success', __("messages.$message", ['name' => $item->name]));
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Technician::findOrFail($id);
        if ($item->company_id != Auth::user()->company_id) {
            return response()->json([
                'message' => __('messages.cant-delete-item-with-different-company')
            ], 403);
        }
        $item->delete();

        return response()->json([
            'message' => __('messages.technician-deleted', ['name' => $item->name])
        ]);
    }
}
