<?php

declare(strict_types=1);

use App\Containers\Common\Auth\Middlewares\AuthMiddleware;
use App\Containers\Common\Subscriptions\UI\Api\Controllers\SubscriptionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthMiddleware::class)->prefix('common')->name('common.')->group(function (): void {
    Route::apiResource('subscriptions', SubscriptionsController::class);
});
