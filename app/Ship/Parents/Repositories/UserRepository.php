<?php

declare(strict_types=1);

namespace App\Ship\Parents\Repositories;

use App\Ship\Parents\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class: UsersRepository.
 *
 * @see AbstractRepository
 * @abstract
 */
class UserRepository extends AbstractRepository
{
    /**
     * @param mixed $by
     * @param mixed $value
     *
     * @return Builder
     */
    public function getBy($by, $value): Builder
    {
        $query = $this->model::query();

        return $query->where($by, $value);
    }

    /**
     * @return Collection|Paginator
     */
    public function getAll(): Collection|Paginator
    {
        $query = $this->model::query();

        return parent::paginateOrLimit($query);
    }

    /**
     * @return string
     */
    protected function modelClass(): string
    {
        return User::class;
    }
}
