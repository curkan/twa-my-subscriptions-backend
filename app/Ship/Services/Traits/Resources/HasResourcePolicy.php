<?php

declare(strict_types=1);

namespace App\Ship\Services\Traits\Resources;

use App\Ship\Services\Authorization\ResourceActionsCollector;

trait HasResourcePolicy
{
    /**
     * @param mixed $resource
     *
     * @return array
     */
    public function resourcePolicyActions(mixed $resource): array
    {
        return $this->policyActions($resource, ['update:*', 'delete', 'create']);
    }

    /**
     * @param mixed $resource
     *
     * @return array
     */
    public function resourcePolicyActionsWithoutModelAttributes(mixed $resource): array
    {
        return $this->policyActions($resource, ['update:*', 'delete', 'create'], true);
    }

    /**
     * @param mixed $resource
     *
     * @return array
     */
    public function collectionPolicyActions(mixed $resource): array
    {
        return $this->policyActions($resource, ['create:*']);
    }

    /**
     * @param mixed $resource
     * @param array $actions
     * @param bool $withoutModelAttributes
     *
     * @return array
     */
    public function policyActions(mixed $resource, array $actions = ['create', 'update', 'delete'], bool $withoutModelAttributes = false): array
    {
        return app(ResourceActionsCollector::class, compact('resource', 'withoutModelAttributes'))->collect($actions);
    }
}
