<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $request,
            ['name', 'first_name', 'last_name', 'email', 'phone'],
            ['role', 'status'],
            ['created_at', 'name', 'email']
        );
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function create(array $data): User
    {
        if (empty($data['name'])) {
            $data['name'] = trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? ''));
        }

        /** @var User $user */
        $user = $this->repository->create($data);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (isset($data['first_name']) || isset($data['last_name'])) {
            $first = $data['first_name'] ?? $user->first_name;
            $last = $data['last_name'] ?? $user->last_name;
            $data['name'] = trim("{$first} {$last}");
        }

        /** @var User $user */
        $user = $this->repository->update($user, $data);

        return $user;
    }

    public function delete(User $user): bool
    {
        return $this->repository->delete($user);
    }
}
