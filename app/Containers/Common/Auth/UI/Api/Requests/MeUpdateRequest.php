<?php

declare(strict_types=1);

namespace App\Containers\Common\Auth\UI\Api\Requests;

use App\Ship\Parents\Requests\Request;

final class MeUpdateRequest extends Request
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
            'settings.*' => 'sometimes',
            'settings.notify_to_bot' => 'sometimes|bool',
        ];
    }
}
