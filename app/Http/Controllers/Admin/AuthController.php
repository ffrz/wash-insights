<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function _logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->setLastActivity('Logout');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function login(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return inertia('admin/auth/Login');
        }

        // kode dibawah ini untuk handle post

        $validator = Validator::make($request->all(), [
            'company_code' => 'required|string|exists:companies,code',
            'username' => 'required',
            'password' => 'required',
        ], [
            'company_code.required' => 'Nama Perusahaan harus diisi.',
            'company_code.exists' => 'Nama Perusahaan tidak ditemukan.',
            'username.required' => 'ID Pengguna harus diisi.',
            'password.required' => 'Kata sandi harus diisi.',
        ]);

        // basic validations
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // extra validations
        $company = Company::where('code', $request->post('company_code', ''))->first();
        $data = $request->only(['username', 'password']);
        $data['company_id'] = $company->id;

        if (!$company) {
            $validator->errors()->add('company_code', 'Nama perusahaan tidak ditemukan.');
        } else if (!$company->active) {
            $validator->errors()->add('company_code', 'Akun perusahaan tidak aktif.');
        } else if (!Auth::attempt($data, $request->has('remember'))) {
            $validator->errors()->add('username', 'Username atau password salah!');
        } else if (!Auth::user()->active) {
            $validator->errors()->add('username', 'Akun anda tidak aktif. Silahkan hubungi administrator!');
            $this->_logout($request);
        } else {
            Auth::user()->setLastLogin();
            Auth::user()->setLastActivity('Login');
            $request->session()->regenerate();
            return redirect(route('admin.dashboard'));
        }

        return redirect()->back()->withInput()->withErrors($validator);
    }

    public function logout(Request $request)
    {
        $this->_logout($request);
        return redirect('/')->with('success', 'Anda telah logout.');
    }

    public function register(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return inertia('admin/auth/Register');
        }

        $validator = Validator::make($request->all(), [
            'company_code' => 'required|alpha_num|unique:companies,code|min:3|max:40',
            'company_name' => 'required|min:2|max:100',
            'username' => 'required|alpha_num|min:3|max:40',
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|min:2|max:255',
            'password' => 'required|min:5|confirmed',
        ], [
            'company_code.required' => 'Kode harus diisi.',
            'company_code.unique' => 'Kode sudah digunakan.',
            'company_code.alpha_num' => 'Gunakan huruf dan angka saja.',

            'company_name.min' => 'Nama terlalu pendek, minimal 2 karakter.',
            'company_name.max' => 'Nama terlalu panjang, maksimal 100 karakter.',

            'name.min' => 'Nama terlalu pendek, minimal 2 karakter.',
            'name.max' => 'Nama terlalu panjang, maksimal 100 karakter.',

            'email.required' => 'Email harus harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.min' => 'Email terlalu pendek, minimal 2 karakter.',
            'email.max' => 'Email terlalu panjang, maksimal 255 karakter.',

            'username.required' => 'Username harus harus diisi.',
            'username.alpha_num' => 'Gunakan huruf dan angka saja.',
            'username.min' => 'Username terlalu pendek, minimal 2 karakter.',
            'username.max' => 'Username terlalu panjang, maksimal 40 karakter.',

            'password.required' => 'Kata sandi harus diisi.',
            'password.min' => 'Kata sandi terlalu pendek, minimal 5 karakter.',
            'password.confirmed' => 'Kata sandi yang anda konfirmasi salah.',
        ]);

        // basic validations
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::transaction(function () use ($request) {
            $company = new Company();
            $company->code = $request->post('company_code');
            $company->name = $request->post('company_name');
            $company->email = '';
            $company->phone = '';
            $company->address = '';
            $company->active = true;
            $company->save();

            $user = new User();
            $user->fill($request->only(['username', 'name', 'email']));
            $user->company_id = $company->id;
            $user->password = Hash::make($request->post('password'));
            $user->role = User::Role_Admin;
            $user->active = true;
            $user->save();
        });

        return redirect(route('admin.auth.login'))->with('success', 'Pendaftaran berhasil, silahkan masuk.');
    }

    public function forgotPassword(Request $request) {
        if ($request->getMethod() === 'GET') {
            return inertia('admin/auth/ForgotPassword');
        }

    }
}
