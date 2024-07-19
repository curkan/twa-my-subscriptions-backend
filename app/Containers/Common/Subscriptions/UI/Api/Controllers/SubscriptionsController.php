<?php

declare(strict_types=1);

namespace App\Containers\Common\Subscriptions\UI\Api\Controllers;

use App\Containers\Common\Subscriptions\UI\Api\Requests\SubscriptionsStoreRequest;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Parents\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

final class SubscriptionsController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/common/subscriptions",
     *     summary="Get subscriptions",
     *     tags={"Common > subscriptions"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful"
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $subs = Subscription::where('user_id', Auth::id())->get();

        return $this->resourceCollection(JsonResource::collection($subs));
    }

    /**
     * @param SubscriptionsStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(SubscriptionsStoreRequest $request): JsonResponse
    {
        $sub = Subscription::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'amount' => $request->integer('amount'),
            'currency' => 1,
            'start_at' => $request->inputAsDate('start_at'),

        ]);

        return $this->resourceStored(JsonResource::make($sub));
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $sub = Subscription::findOrFail($id);
        $sub->delete();

        return $this->resourceDeleted();
    }
}
