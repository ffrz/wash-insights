<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OperationalCostCategoryController;
use App\Http\Controllers\Admin\OperationalCostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Utils\ArtisanCommandController;
use App\Http\Middleware\Auth;
use App\Http\Middleware\NonAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::get('/test', function () {
    return inertia('Test');
})->name('test');

if (env('APP_RUN_COMMAND_ALLOWED')) {
    Route::prefix('/--cmd')->group(function () {
        Route::prefix('/artisan')->group(function() {
            Route::get('migrate', [ArtisanCommandController::class, 'migrate']);
            Route::get('migrate:rollback', [ArtisanCommandController::class, 'migrateRollback']);
            Route::get('migrate:fresh--seed', [ArtisanCommandController::class, 'migrateFreshSeed']);
        });
    });
}

Route::middleware(NonAuthenticated::class)->group(function () {
    Route::prefix('/admin/auth')->group(function () {
        Route::match(['get', 'post'], 'login', [AuthController::class, 'login'])->name('admin.auth.login');
        Route::match(['get', 'post'], 'register', [AuthController::class, 'register'])->name('admin.auth.register');
        Route::match(['get', 'post'], 'forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.auth.forgot-password');
    });
});

Route::middleware([Auth::class])->group(function () {
    Route::match(['get', 'post'], 'admin/auth/logout', [AuthController::class, 'logout'])->name('admin.auth.logout');

    Route::prefix('admin')->group(function () {
        Route::redirect('', 'admin/dashboard', 301);

        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('test', [DashboardController::class, 'test'])->name('admin.test');
        Route::get('about', function () {
            return inertia('admin/About');
        })->name('admin.about');

        Route::prefix('customers')->group(function () {
            Route::get('', [CustomerController::class, 'index'])->name('admin.customer.index');
            Route::get('data', [CustomerController::class, 'data'])->name('admin.customer.data');
            Route::get('add', [CustomerController::class, 'editor'])->name('admin.customer.add');
            Route::get('duplicate/{id}', [CustomerController::class, 'duplicate'])->name('admin.customer.duplicate');
            Route::get('edit/{id}', [CustomerController::class, 'editor'])->name('admin.customer.edit');
            Route::get('detail/{id}', [CustomerController::class, 'detail'])->name('admin.customer.detail');
            Route::post('save', [CustomerController::class, 'save'])->name('admin.customer.save');
            Route::post('delete/{id}', [CustomerController::class, 'delete'])->name('admin.customer.delete');
        });

        Route::prefix('service-orders')->group(function () {
            Route::get('', [ServiceOrderController::class, 'index'])->name('admin.service-order.index');
            Route::get('data', [ServiceOrderController::class, 'data'])->name('admin.service-order.data');
            Route::get('add', [ServiceOrderController::class, 'editor'])->name('admin.service-order.add');
            Route::get('edit/{id}', [ServiceOrderController::class, 'editor'])->name('admin.service-order.edit');
            Route::get('duplicate/{id}', [ServiceOrderController::class, 'duplicate'])->name('admin.service-order.duplicate');
            Route::get('detail/{id}', [ServiceOrderController::class, 'detail'])->name('admin.service-order.detail');
            Route::post('save', [ServiceOrderController::class, 'save'])->name('admin.service-order.save');
            Route::post('delete/{id}', [ServiceOrderController::class, 'delete'])->name('admin.service-order.delete');
        });

        Route::prefix('technicians')->group(function () {
            Route::get('', [TechnicianController::class, 'index'])->name('admin.technician.index');
            Route::get('data', [TechnicianController::class, 'data'])->name('admin.technician.data');
            Route::get('add', [TechnicianController::class, 'editor'])->name('admin.technician.add');
            Route::get('edit/{id}', [TechnicianController::class, 'editor'])->name('admin.technician.edit');
            Route::get('duplicate/{id}', [TechnicianController::class, 'duplicate'])->name('admin.technician.duplicate');
            Route::get('detail/{id}', [TechnicianController::class, 'detail'])->name('admin.technician.detail');
            Route::post('save', [TechnicianController::class, 'save'])->name('admin.technician.save');
            Route::post('delete/{id}', [TechnicianController::class, 'delete'])->name('admin.technician.delete');
        });

        Route::prefix('operational-cost-categories')->group(function () {
            Route::get('', [OperationalCostCategoryController::class, 'index'])->name('admin.operational-cost-category.index');
            Route::get('data', [OperationalCostCategoryController::class, 'data'])->name('admin.operational-cost-category.data');
            Route::get('add', [OperationalCostCategoryController::class, 'editor'])->name('admin.operational-cost-category.add');
            Route::get('duplicate/{id}', [OperationalCostCategoryController::class, 'duplicate'])->name('admin.operational-cost-category.duplicate');
            Route::get('edit/{id}', [OperationalCostCategoryController::class, 'editor'])->name('admin.operational-cost-category.edit');
            Route::post('save', [OperationalCostCategoryController::class, 'save'])->name('admin.operational-cost-category.save');
            Route::post('delete/{id}', [OperationalCostCategoryController::class, 'delete'])->name('admin.operational-cost-category.delete');
        });

        Route::prefix('operational-costs')->group(function () {
            Route::get('', [OperationalCostController::class, 'index'])->name('admin.operational-cost.index');
            Route::get('data', [OperationalCostController::class, 'data'])->name('admin.operational-cost.data');
            Route::get('add', [OperationalCostController::class, 'editor'])->name('admin.operational-cost.add');
            Route::get('duplicate/{id}', [OperationalCostController::class, 'duplicate'])->name('admin.operational-cost.duplicate');
            Route::get('edit/{id}', [OperationalCostController::class, 'editor'])->name('admin.operational-cost.edit');
            Route::post('save', [OperationalCostController::class, 'save'])->name('admin.operational-cost.save');
            Route::post('delete/{id}', [OperationalCostController::class, 'delete'])->name('admin.operational-cost.delete');
        });


        Route::prefix('settings')->group(function () {
            Route::get('profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
            Route::post('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
            Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('admin.profile.update-password');

            Route::get('company-profile/edit', [CompanyProfileController::class, 'edit'])->name('admin.company-profile.edit');
            Route::post('company-profile/update', [CompanyProfileController::class, 'update'])->name('admin.company-profile.update');

            Route::prefix('users')->group(function () {
                Route::get('', [UserController::class, 'index'])->name('admin.user.index');
                Route::get('data', [UserController::class, 'data'])->name('admin.user.data');
                Route::get('add', [UserController::class, 'editor'])->name('admin.user.add');
                Route::get('edit/{id}', [UserController::class, 'editor'])->name('admin.user.edit');
                Route::get('duplicate/{id}', [UserController::class, 'duplicate'])->name('admin.user.duplicate');
                Route::post('save', [UserController::class, 'save'])->name('admin.user.save');
                Route::post('delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
                Route::get('detail/{id}', [UserController::class, 'detail'])->name('admin.user.detail');
            });
        });
    });
});
