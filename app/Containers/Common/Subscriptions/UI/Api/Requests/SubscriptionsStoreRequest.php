<?php

declare(strict_types=1);

namespace App\Containers\Common\Subscriptions\UI\Api\Requests;

use App\Ship\Parents\Enums\Subscription\SubscriptionPeriodEnum;
use App\Ship\Parents\Requests\Request;
use Illuminate\Validation\Rule;
use Str;

/**
 * @OA\RequestBody(
 *  request="SettingsCategory_store",
 *  required=true,
 *  @OA\MediaType(
 *    mediaType="application/json",
 *    @OA\Schema(
 *      required={"name"},
 *   	@OA\Property(property="name", type="string", example="name_category"),
 *   	@OA\Property(property="description", type="string", example="description category"),
 *    )
 *  )
 * )
 */
final class SubscriptionsStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|sometimes',
            'amount' => 'required|integer',
            'start_at' => 'required|sometimes',
            'url' => 'sometimes',
            'period' => Rule::in(SubscriptionPeriodEnum::values()),
            'pan' => 'integer',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['period' => Str::lower($this->input('period'))]);
    }
}
