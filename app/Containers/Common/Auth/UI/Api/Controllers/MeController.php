<?php

declare(strict_types=1);

namespace App\Containers\Common\Auth\UI\Api\Controllers;

use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class MeController extends ApiController
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();

        return response_json('data', [
            'id' => $user->getKey(),
            'photo_url' => $user->photo_url,
            'settings' => [
                'notify_to_bot' => $user->notify_to_bot,
            ],
        ], []);
    }
}
