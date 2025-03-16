<?php

namespace App\Actions\Workstream;

use App\DTO\Workstream\UpdateWorkstreamDTO;
use App\Models\Workstream;

class UpdateWorkstream
{
    /**
     * Update exiting Workstream.
     *
     * @param UpdateWorkstreamDTO $updateWorkstreamDTO=
     *
     * @return Workstream
     */
    public function __invoke(
        UpdateWorkstreamDTO $updateWorkstreamDTO
    ): Workstream {

        $workstream = $updateWorkstreamDTO->organization->workstreams()->findOrFail($updateWorkstreamDTO->workstream_id);
        $workstream->update([
            'name' => $updateWorkstreamDTO->name,
            'priority_id' => $updateWorkstreamDTO->priority_id,
        ]);

        return $workstream;

    }
}
