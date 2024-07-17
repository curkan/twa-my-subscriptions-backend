<?php

declare(strict_types=1);

namespace App\Ship\Services\Authorization;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class ResourceAttributesGuard
{
    /**
     * @var mixed
     */
    private mixed $resource;

    /**
     * @var array
     */
    private array $policies = [];

    /**
     * @param mixed $resource
     * @param string $attribute
     *
     * @return bool
     */
    public function canView(mixed $resource, string $attribute): bool
    {
        $this->resource = $resource;

        return $this->can('view', $attribute);
    }

    /**
     * @param mixed $resource
     * @param string $attribute
     *
     * @return bool
     */
    public function canUpdate(mixed $resource, string $attribute): bool
    {
        $this->resource = $resource;

        return $this->can('update', $attribute);
    }

    /**
     * @param mixed $resource
     * @param string $attribute
     *
     * @return bool
     */
    public function canCreate(mixed $resource, string $attribute): bool
    {
        $this->resource = $resource;

        return $this->can('create', $attribute);
    }

    /**
     * @param string $action
     * @param string $attribute
     *
     * @return bool
     */
    private function can(string $action, string $attribute): bool
    {
        $policy = $this->getPolicyFor($this->resource);
        if (empty($policy)) {
            return false;
        }

        foreach ($this->getVerifiablePolicyActions($action, $attribute) as $method) {
            if (!method_exists($policy, $method)) {
                continue;
            }

            return Gate::allows($method, $this->resource);
        }

        return false;
    }

    /**
     * @param object|string $resource
     * @return object|null
     */
    private function getPolicyFor(object|string $resource): ?object
    {
        $resource = is_object($resource) ? $resource::class : $resource;
        if (!is_string($resource)) {
            return null;
        }

        if (!isset($this->policies[$resource])) {
            $policy = Gate::getPolicyFor($this->resource);
            $this->policies[$resource] = is_object($policy) ? $policy : null;
        }

        return $this->policies[$resource];
    }

    /**
     * @param string $action
     * @param string $attribute
     * @return array
     */
    private function getVerifiablePolicyActions(string $action, string $attribute): array
    {
        return [
            Str::camel($action . ucfirst($attribute)),
            $action . 'Any',
            $action,
        ];
    }
}
