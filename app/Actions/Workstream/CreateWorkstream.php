<?php

namespace App\Actions\Workstream;

use App\DTO\Workstream\CreateWorkstreamDTO;
use App\Models\Workstream;

class CreateWorkstream
{
    /**
     * Creates new workstream.
     *
     * @param CreateWorkstreamDTO $createWorkstreamDTO
     *
     * @return Workstream
     */
    public function __invoke(
        CreateWorkstreamDTO $createWorkstreamDTO
    ): Workstream {

        return $createWorkstreamDTO->organization->workstreams()->create([
            'name' => $createWorkstreamDTO->name,
            'priority_id' => $createWorkstreamDTO->priority_id,
        ]);

    }
}
