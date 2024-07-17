<?php

declare(strict_types=1);

namespace App\Ship\Parents\Repositories;

use App\Ship\Core\Abstracts\Repositories\Repository;
use App\Ship\Parents\Models\Model;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class: AbstractRepository.
 *
 * @see Repository
 * @abstract
 */
abstract class AbstractRepository extends Repository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * AbstractRepository constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->model = app($this->modelClass());

        $this->request = $request;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model::query();
    }

    /**
     * @param mixed $id
     * @param Builder|null $query
     *
     * @return EloquentCollection|Model
     */
    public function get($id, ?Builder $query = null): EloquentCollection|Model
    {
        return ($query ?: $this->query())->findOrFail($id);
    }

    /**
     * @param int|null $perPage
     * @param Builder|null $query
     *
     * @return Paginator
     */
    public function paginate(?int $perPage = null, ?Builder $query = null): Paginator
    {
        $query = $query ?: $this->query();
        if ($query instanceof Paginator) {
            return $query;
        }

        return $query->paginate(per_page_normalize($perPage));
    }

    /**
     * @param Builder|null $query
     *
     * @return Collection|Paginator
     */
    public function paginateOrLimit(?Builder $query = null): Collection|Paginator
    {
        $query = $query ?: $this->query();
        if ($query instanceof Paginator) {
            return $query;
        }

        if (has_pagination()) {
            return $this->paginate((int) $this->request->get('per_page'), $query);
        }

        $limit = property_exists($query->getModel(), 'limit') ? (int) $query->getModel()->limit : config('app.collection_max_size');
        if (!empty($query->getQuery()->limit) && $query->getQuery()->limit <= $limit) {
            return $query->get();
        }

        return $query
            ->limit($limit)
            ->get();
    }

    /**
     * @return string
     */
    abstract protected function modelClass(): string;
}
