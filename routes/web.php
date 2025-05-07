<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OperationalCostCategoryController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\OperationalCostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WashOrderController;
use App\Http\Controllers\Admin\WashServiceController;
use App\Http\Controllers\Utils\ArtisanCommandController;
use App\Http\Middleware\Auth;
use App\Http\Middleware\NonAuthenticated;
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

        Route::prefix('products')->group(function () {
            Route::get('', [ProductController::class, 'index'])->name('admin.product.index');
            Route::get('data', [ProductController::class, 'data'])->name('admin.product.data');
            Route::get('add', [ProductController::class, 'editor'])->name('admin.product.add');
            Route::get('duplicate/{id}', [ProductController::class, 'duplicate'])->name('admin.product.duplicate');
            Route::get('edit/{id}', [ProductController::class, 'editor'])->name('admin.product.edit');
            Route::post('save', [ProductController::class, 'save'])->name('admin.product.save');
            Route::post('delete/{id}', [ProductController::class, 'delete'])->name('admin.product.delete');
            Route::get('detail/{id}', [ProductController::class, 'detail'])->name('admin.product.detail');
        });

        Route::prefix('product-categories')->group(function () {
            Route::get('', [ProductCategoryController::class, 'index'])->name('admin.product-category.index');
            Route::get('data', [ProductCategoryController::class, 'data'])->name('admin.product-category.data');
            Route::get('add', [ProductCategoryController::class, 'editor'])->name('admin.product-category.add');
            Route::get('duplicate/{id}', [ProductCategoryController::class, 'duplicate'])->name('admin.product-category.duplicate');
            Route::get('edit/{id}', [ProductCategoryController::class, 'editor'])->name('admin.product-category.edit');
            Route::post('save', [ProductCategoryController::class, 'save'])->name('admin.product-category.save');
            Route::post('delete/{id}', [ProductCategoryController::class, 'delete'])->name('admin.product-category.delete');
        });

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

        Route::prefix('suppliers')->group(function () {
            Route::get('', [SupplierController::class, 'index'])->name('admin.supplier.index');
            Route::get('data', [SupplierController::class, 'data'])->name('admin.supplier.data');
            Route::get('add', [SupplierController::class, 'editor'])->name('admin.supplier.add');
            Route::get('duplicate/{id}', [SupplierController::class, 'duplicate'])->name('admin.supplier.duplicate');
            Route::get('edit/{id}', [SupplierController::class, 'editor'])->name('admin.supplier.edit');
            Route::get('detail/{id}', [SupplierController::class, 'detail'])->name('admin.supplier.detail');
            Route::post('save', [SupplierController::class, 'save'])->name('admin.supplier.save');
            Route::post('delete/{id}', [SupplierController::class, 'delete'])->name('admin.supplier.delete');
        });

        Route::prefix('wash-services')->group(function () {
            Route::get('', [WashServiceController::class, 'index'])->name('admin.wash-service.index');
            Route::get('data', [WashServiceController::class, 'data'])->name('admin.wash-service.data');
            Route::get('add', [WashServiceController::class, 'editor'])->name('admin.wash-service.add');
            Route::get('duplicate/{id}', [WashServiceController::class, 'duplicate'])->name('admin.wash-service.duplicate');
            Route::get('edit/{id}', [WashServiceController::class, 'editor'])->name('admin.wash-service.edit');
            Route::get('detail/{id}', [WashServiceController::class, 'detail'])->name('admin.wash-service.detail');
            Route::post('save', [WashServiceController::class, 'save'])->name('admin.wash-service.save');
            Route::post('delete/{id}', [WashServiceController::class, 'delete'])->name('admin.wash-service.delete');
        });

        Route::prefix('wash-orders')->group(function () {
            Route::get('', [WashOrderController::class, 'index'])->name('admin.wash-order.index');
            Route::get('data', [WashOrderController::class, 'data'])->name('admin.wash-order.data');
            Route::get('add', [WashOrderController::class, 'editor'])->name('admin.wash-order.add');
            Route::get('edit/{id}', [WashOrderController::class, 'editor'])->name('admin.wash-order.edit');
            Route::get('duplicate/{id}', [WashOrderController::class, 'duplicate'])->name('admin.wash-order.duplicate');
            Route::get('detail/{id}', [WashOrderController::class, 'detail'])->name('admin.wash-order.detail');
            Route::post('save', [WashOrderController::class, 'save'])->name('admin.wash-order.save');
            Route::post('delete/{id}', [WashOrderController::class, 'delete'])->name('admin.wash-order.delete');
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
