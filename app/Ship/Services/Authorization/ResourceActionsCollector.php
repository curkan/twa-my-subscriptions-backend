<?php

declare(strict_types=1);

namespace App\Ship\Services\Authorization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

final class ResourceActionsCollector
{
    /**
     * @var mixed
     */
    private mixed $resource;

    /**
     * @var array
     */
    private array $actions = [];

    /**
     * @var bool
     */
    private bool $withoutModelAttributes = false;

    /**
     * @var array
     */
    private array $baseActions = [
        'create',
        'update',
        'delete',
    ];

    /**
     * @var array
     */
    private array $skipAttributes = [
        'id',
        'ID',
    ];

    /**
     * PolicyAbilitiesInspector constructor.
     *
     * @param mixed $resource
     * @param bool $withoutModelAttributes
     */
    public function __construct(mixed $resource, bool $withoutModelAttributes = false)
    {
        $this->resource = $resource;
        $this->withoutModelAttributes = $withoutModelAttributes;
    }

    /**
     * @param mixed $resource
     *
     * @return static
     */
    public static function make(mixed $resource): self
    {
        return new self($resource);
    }

    /**
     * @param array $actions
     * @return array
     */
    public function collect(array $actions = []): array
    {
        $this->actions = $actions ?: $this->baseActions;

        if (empty($this->actions)) {
            return [];
        }

        return array_merge(
            $this->collectResourceActions(),
            $this->withoutModelAttributes ? [] : $this->collectAttributesActions()
        );
    }

    /**
     * @return array
     */
    private function collectResourceActions(): array
    {
        $actions = array_unique(array_map(fn ($action) => $this->normalizeResourceAction($action), $this->actions));

        return array_values(array_filter($actions, function ($action) {
            return Gate::inspect($action, $this->resource)->allowed();
        }));
    }

    /**
     * @return array
     */
    private function collectAttributesActions(): array
    {
        $this->tryResolveResource();
        if (!is_object($this->resource)) {
            return [];
        }

        $attributeActions = [];
        $attributesGuard = app(ResourceAttributesGuard::class);

        foreach ($this->actions as $action) {
            if ($this->notCollectAttributesActions($action)) {
                continue;
            }

            [$action, $attribute] = $this->normalizeAttributeAction($action);
            if (blank($attribute)) {
                continue;
            }

            $attributes = $attribute === '*' ? $this->getAttributes() : [$attribute];
            foreach ($attributes as $attribute) {
                $allowedMethod = 'can' . ucfirst($action);
                if (!method_exists($attributesGuard, $allowedMethod) || !$attributesGuard->{$allowedMethod}($this->resource, $attribute)) {
                    continue;
                }

                $attribute = method_exists($this->resource, 'aliasByAttribute')
                    ? $this->resource->aliasByAttribute($attribute)
                    : $attribute;

                $attributeActions[] = $action . ':' . $attribute;
            }
        }

        return $attributeActions;
    }

    /**
     * @return void
     */
    private function tryResolveResource(): void
    {
        if (is_object($this->resource)) {
            return;
        }

        if (is_string($this->resource) && class_exists($this->resource)) {
            $this->resource = app($this->resource);
        }
    }

    /**
     * @return array
     */
    private function getAttributes(): array
    {
        if (!method_exists($this->resource, 'getFillable')) {
            return [];
        }

        $attributes = ($this->resource instanceof Model && method_exists($this->resource, 'getOriginalFillable'))
            ? $this->resource->getOriginalFillable()
            : $this->resource->getFillable();

        return array_filter($attributes, fn ($attribute) => !in_array($attribute, $this->skipAttributes));
    }

    /**
     * @param string $action
     * @return bool
     */
    private function notCollectAttributesActions(string $action): bool
    {
        return !Str::contains($action, ':');
    }

    /**
     * @param string $action
     * @return string
     */
    private function normalizeResourceAction(string $action): string
    {
        return Str::before($action, ':');
    }

    /**
     * @param string $action
     * @return array
     */
    private function normalizeAttributeAction(string $action): array
    {
        return explode(':', $action);
    }
}
