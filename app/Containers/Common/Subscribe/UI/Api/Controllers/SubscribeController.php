<?php

declare(strict_types=1);

namespace App\Containers\Common\Subscribe\UI\Api\Controllers;

use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return response_json('test', [
            'result' => Auth::user()->getKey(),
        ], []);
    }
}
