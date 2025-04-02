<flux:modal name="workstream-members-modal" :dismissible="false" wire:model.self="$showWorkstreamTeamModal"  @close="onModalClose">

        <form  wire:submit="add">
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-start sm:justify-between sm:space-x-2 sm:space-y-0">
                <div class="w-full">
                    <flux:select wire:model="email" label="email" variant="combobox" clearable :filter="false">
                        <x-slot name="input">
                            <flux:select.input wire:model.live.debounce.150ms="search" placeholder="Search by Name o email" />
                        </x-slot>
                        @foreach($autocomplete as $user)
                            <flux:select.option>{{ $user->email  }}</flux:select.option>
                        @endforeach

                    </flux:select>
                </div>
                <div class='w-full sm:w-64'>
                    <livewire:role-dropdown wire:model="role_id" key="role-dropdown" />
                    <flux:error name="role_id"/>
                </div>
                <div class="sm:mt-6.5">
                    <flux:button type="submit" icon="user-plus" variant="filled">Add</flux:button>
                </div>
            </div>
        </form >
        <div class="mt-5">
            @if(count($members) > 0)
            <flux:table :paginate="$members">
                    <flux:table.columns>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column >Sent</flux:table.column>
                        <flux:table.column >Role</flux:table.column>
                        <flux:table.column ></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($members as $member)
                            <flux:table.row :key="$member->id">
                                <flux:table.cell class="flex items-center gap-3">
                                    {{ $member->email }}
                                    @if($member->is_expired)
                                        <flux:badge size="sm" color="zinc" inset="top bottom">Expired</flux:badge>
                                    @endif
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">
                                    @if($member->sent_at)
                                        {{ $member->sent_at->toDayDateTimeString() }}
                                    @endif
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ $member->role->name }}
                                </flux:table.cell>


                                <flux:table.cell class="text-right space-x-1">
                                    <flux:tooltip content="{{ $member->can_resend ? 'Resend invite' : 'Please wait to resend the invite' }}">
                                    <span>
                                        <flux:button
                                            class="cursor-pointer"
                                            size="sm"
                                            icon="{{ $member->can_resend ? 'send' : 'clock' }}"
                                            variant="primary"
                                            :disabled="!$member->can_resend"
                                            wire:click="resend({{ $member->id  }})"
                                            dusk="resend"
                                        >Resend</flux:button>
                                    </span>
                                    </flux:tooltip>
                                    <flux:button
                                        class="cursor-pointer"
                                        size="sm"
                                        icon="trash"
                                        variant="danger"
                                        wire:click="delete({{ $member->id  }})"
                                        wire:confirm="Are you sure you want to cancel this invite?"
                                    >Cancel</flux:button>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @else
                <x-table-no-data :showClear="false">
                    No members
                </x-table-no-data>
            @endif
        </div>

</flux:modal>
