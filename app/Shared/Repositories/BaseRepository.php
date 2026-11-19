<?php

namespace App\Shared\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseRepository
{
    public function __construct(protected Model $model) {}

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function findByUuid(string $uuid): ?Model
    {
        return $this->query()->where('uuid', $uuid)->first();
    }

    public function findByUuidOrFail(string $uuid): Model
    {
        return $this->query()->where('uuid', $uuid)->firstOrFail();
    }

    public function create(array $data): Model
    {
        return $this->query()->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model->fresh();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    public function paginate(Request $request, array $searchable = [], array $filterable = [], array $sortable = ['created_at'], string $defaultSort = 'created_at'): LengthAwarePaginator
    {
        $query = $this->query();

        if ($search = $request->string('search')->toString()) {
            $query->where(function (Builder $builder) use ($search, $searchable): void {
                foreach ($searchable as $column) {
                    $builder->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        foreach ($filterable as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->input($column));
            }
        }

        $sortBy = $request->string('sort_by')->toString() ?: $defaultSort;
        if (! in_array($sortBy, $sortable, true)) {
            $sortBy = $defaultSort;
        }

        $sortDir = strtolower($request->string('sort_dir')->toString()) === 'asc' ? 'asc' : 'desc';
        $perPage = min(max((int) $request->input('per_page', 15), 1), 100);

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }
}
