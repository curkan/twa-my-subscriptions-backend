<?php

declare(strict_types=1);

use App\Containers\Common\Subscribe\UI\Api\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::prefix('common')->name('common.')->group(function (): void {
    Route::post('subscribes', SubscribeController::class);
});
