<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyProfileController extends Controller
{
    public function __construct()
    {
        allowed_roles(User::Role_Admin);
    }

    /**
     * Display the company's profile form.
     */
    public function edit()
    {
        $data = Company::find(Auth::user()->company_id);
        return inertia('admin/company-profile/Edit', compact('data'));
    }

    /**
     * Update the company's profile information.
     */
    public function update(Request $request)
    {
        Auth::user()->setLastActivity('Memperbarui profil perusahaan');

        $rules = [
            'name' => 'required|min:2|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|regex:/^(\+?\d{1,4})?[\s.-]?\(?\d{1,4}\)?[\s.-]?\d{1,4}[\s.-]?\d{1,9}$/|max:40',
            'address' => 'max:1000',
        ];

        $fields = $request->only(['name', 'email', 'phone', 'address']);
        $fields['email'] = $fields['email'] ?? '';
        $fields['phone'] = $fields['phone'] ?? '';
        $fields['address'] = $fields['address'] ?? '';
        $request->validate($rules);
        $company = Company::find(Auth::user()->company_id);
        $company->fill($fields);
        $company->save();

        $request->session()->flash('success', __('messages.update-company-profile-success'));

        return back();
    }
}
