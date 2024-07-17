<?php

declare(strict_types=1);

namespace App\Ship\Services\Contracts\Resources;

interface HasActionsContract
{
    /**
     * @return array
     */
    public function actions(): array;
}
