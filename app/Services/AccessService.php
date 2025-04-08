<?php

namespace App\Services;

use App\Actions\Access\GrantAccess;
use App\Actions\Access\RevokeAccess;
use App\Contracts\AccessibleContract;
use App\DTO\Access\GrantAccessDTO;
use App\DTO\Access\RevokeAccessDTO;
use App\Exceptions\GrantAccessException;
use App\Models\Access;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AccessService
{
    public function __construct(
        protected UserService $userService,
        protected GrantAccess $grantAccess,
        protected RevokeAccess $revokeAccess,
    ) {}

    public function list(
        AccessibleContract $accessible,
        ?int $pageSize = null
    ): LengthAwarePaginator {

        return $accessible->accesses()->with('user', 'role')
            ->leftJoin('users', 'accesses.user_id', '=', 'users.id')
            ->orderBy('users.first_name')
            ->orderBy('users.last_name')
            ->select('accesses.*') // make sure to select workstreams columns
            ->paginate($pageSize ?? config('app.pagination_items'));
    }

    public function listAutoComplete(AccessibleContract $accessible, string $search): Collection
    {

        return $this->userService->getCurrentOrganization()
            ->users()
            ->whereNotExists(function (Builder $query) use ($accessible) {
                $query->select(DB::raw(1))
                    ->from('accesses')
                    ->whereColumn('accesses.user_id', 'users.id')
                    ->where('accesses.accessible_id', '=', $accessible->id)
                    ->where('accesses.accessible_type', '=', $accessible::class);
            })->whereAny([
                'first_name',
                'last_name',
                'email',
            ], 'like', "%$search%")
            ->limit(10)
            ->get();

    }

    /**
     * Great Access.
     *
     * @param GrantAccessDTO      $grantAccessDTO
     * @param OrganizationService $organizationService
     *
     * @return Access
     *
     * @throws GrantAccessException
     */
    public function grantAccess(
        GrantAccessDTO $grantAccessDTO,
        OrganizationService $organizationService
    ): Access {
        return ($this->grantAccess)(
            grantAccessDTO: $grantAccessDTO,
            organizationService: $organizationService
        );
    }

    /**
     * Revoke Access.
     *
     * @param RevokeAccessDTO $revokeAccessDTO
     *
     */
    public function revokeAccess(
        RevokeAccessDTO $revokeAccessDTO,
    ): void {
        ($this->revokeAccess)(
            revokeAccessDTO: $revokeAccessDTO,
        );
    }
}
