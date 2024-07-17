<?php

declare(strict_types=1);

namespace App\Ship\Services\Contracts\Resources;

interface HasMetaContract
{
    /**
     * @return array
     */
    public function meta(): array;
}
