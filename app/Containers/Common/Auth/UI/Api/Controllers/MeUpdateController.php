<?php

declare(strict_types=1);

namespace App\Containers\Common\Auth\UI\Api\Controllers;

use App\Containers\Common\Auth\UI\Api\Requests\MeUpdateRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class MeUpdateController extends ApiController
{
    /**
     * @param MeUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function __invoke(MeUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $dataToUpdate = [];

        if (!empty($request->input('settings'))) {
            $dataToUpdate['notify_to_bot'] = $request->input('settings')['notify_to_bot'];
        }

        $user->update($dataToUpdate);

        return response_json('data', [
            'id' => $user->getKey(),
            'photo_url' => $user->photo_url,
            'settings' => [
                'notify_to_bot' => $user->notify_to_bot,
            ],
        ], []);
    }
}
