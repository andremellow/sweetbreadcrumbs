<?php

namespace App\Actions\Workstream;

use App\DTO\Workstream\DeleteWorkstreamDTO;
use App\Models\Workstream;

class DeleteWorkstream
{
    /**
     * Delete exiting Workstream.
     *
     * @param DeleteWorkstreamDTO $deleteWorkstreamDTO=
     *
     * @return Delete
     */
    public function __invoke(
        DeleteWorkstreamDTO $deleteWorkstreamDTO
    ): void {
        $deleteWorkstreamDTO->workstream->delete();
    }
}
