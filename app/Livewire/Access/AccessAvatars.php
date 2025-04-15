<?php

namespace App\Livewire\Access;

use App\Contracts\AccessibleContract;
use App\Enums\EventEnum;
use App\Services\AccessService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::ACCESS_GRANTED->value, EventEnum::ACCESS_REVOKED->value])]
class AccessAvatars extends Component
{
    public AccessibleContract $accessible;

    #[Computed]
    public function members(): LengthAwarePaginator
    {
        $accessServices = app(AccessService::class);

        return $accessServices->list(
            accessible: $this->accessible,
            pageSize: 3
        );

    }

    public function render(): View
    {
        return view('livewire.access.access-avatars');
    }
}
