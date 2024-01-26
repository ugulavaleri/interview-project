<?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\CheckoutController;
    use App\Http\Controllers\ForgotPasswordController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\ResetPasswordController;
    use Illuminate\Support\Facades\Route;

    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::group(['middleware' => 'api'], function(){
        Route::prefix('/cart')->group(function () {
            Route::post('{product}', [CartController::class, 'add']);
            Route::delete('{product}', [CartController::class, 'delete']);
        });

        Route::prefix('password/')->group(function () {
            Route::post('forgot', [AuthController::class, 'forgetPassword']);
            Route::post('reset', [AuthController::class, 'resetPassword']);
        });

        Route::post('checkout', [CheckoutController::class, 'checkOut']);
    });

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
