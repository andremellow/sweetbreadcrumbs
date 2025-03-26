<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Invites')" :subheading="__('See who want to work with you... :)')">
    <flux:card>
        <div class="w-full">
            @if(count($invites) > 0)
            <flux:table :paginate="$invites">
                <flux:table.columns>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column >Sent</flux:table.column>
                    <flux:table.column >Role</flux:table.column>
                    <flux:table.column ></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($invites as $invite)
                        <flux:table.row :key="$invite->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{ $invite->email }}
                                @if($invite->is_expired)
                                    <flux:badge size="sm" color="zinc" inset="top bottom">Expired</flux:badge>  
                                @endif
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap">
                                @if($invite->sent_at)
                                    {{ $invite->sent_at->toDayDateTimeString() }}
                                @endif
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $invite->role->name }}
                            </flux:table.cell>

                            
                            <flux:table.cell class="text-right space-x-1">
                                @if(!$invite->is_expired)
                                        <flux:button 
                                            class="cursor-pointer"
                                            size="sm"
                                            icon="user-plus" 
                                            variant="primary"
                                            wire:click="accept({{ $invite->id  }})"
                                            dusk="accept"
                                        >{{ __('Accept') }}</flux:button>
                                        @endif
                                <flux:button 
                                    class="cursor-pointer"
                                    size="sm"
                                    icon="trash" 
                                    variant="danger"
                                    wire:click="decline({{ $invite->id  }})"
                                    wire:confirm="Are you sure you want to decline this invite?"
                                >{{ __('Decline') }}</flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            @else
                <x-table-no-data :showClear="false">
                    No pending invites
                </x-table-no-data>
            @endif
        </div>
        </flux:card>
    </x-settings.layout>
</section>
