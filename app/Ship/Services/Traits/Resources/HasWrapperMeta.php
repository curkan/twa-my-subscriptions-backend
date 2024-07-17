<?php

declare(strict_types=1);

namespace App\Ship\Services\Traits\Resources;

use App\Ship\Services\Contracts\Resources\HasMetaContract;
use ReflectionException;

trait HasWrapperMeta
{
    /**
     * @param array $attributes
     *
     * @throws ReflectionException
     *
     * @return array
     */
    protected function _wrap(array $attributes): array
    {
        $attributes['meta'] = $this->_meta();

        return $attributes;
    }

    /**
     * @throws ReflectionException
     *
     * @return array
     */
    protected function _meta(): array
    {
        $meta = [];

        if ($this instanceof HasMetaContract) {
            $meta = array_merge($meta, $this->meta());
        }

        return $meta;
    }
}
