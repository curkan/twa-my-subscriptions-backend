<?php

declare(strict_types=1);

namespace App\Containers\Common\Subscribe\UI\Api\Controllers;

use App\Containers\Common\Subscribe\Actions\Validate;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SubscribeController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/common/subscribes",
     *     summary="Get terms",
     *     tags={"Common > Subscribes"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     )
     * )
     *
     * @return JsonResponse
     * @param Request $request
     */
    public function __invoke(Request $request): JsonResponse
    {
        $botToken = env('BOT_TOKEN');

        $valid = false;
        if (Validate::isSafe($botToken, $request->input('auth'))) {
            $valid = true;
        } else {
            $valid = false;
        }

        return response_json('test', [
            'result' => $valid,
        ], []);
    }
}
