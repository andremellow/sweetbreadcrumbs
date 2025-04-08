<flux:modal class="w-full lg:w-2/3" variant="flyout" name="member-access-modal" :dismissible="false" wire:model.self="showMemberAccessModal"  @close="onModalClose">
        <form  wire:submit="add" >
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-start sm:justify-between sm:space-x-2 sm:space-y-0">
                <div class="w-full">
                    <flux:select wire:model="email" label="Email" variant="combobox" clearable :filter="false">
                        <x-slot name="input">
                            <flux:select.input wire:model.live.debounce.150ms="search" placeholder="Search by Name o email" />
                        </x-slot>
                        @foreach($autocomplete as $userAutoComplete)
                            <flux:select.option value="{{ $userAutoComplete->email }}" >{{ $userAutoComplete->last_name  }}, {{ $userAutoComplete->first_name }}  {{ $userAutoComplete->email  }}</flux:select.option>
                        @endforeach

                    </flux:select>
                </div>
                <div class='w-full sm:w-64'>
                    <livewire:role-dropdown wire:model="roleId" key="role-dropdown" with-user="true" :$user />
                    <flux:error name="roleId"/>

                </div>
                <div class="sm:mt-6.5">
                    <flux:button type="submit" icon="user-plus" variant="filled">Add</flux:button>
                </div>
            </div>
        </form >
        <div class="mt-5">
            @if(count($this->members) > 0)
            <flux:table :paginate="$this->members">
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column class="hidden sm:table-cell" >Since</flux:table.column>
                        <flux:table.column >Role</flux:table.column>
                        <flux:table.column ></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($this->members as $member)
                            <flux:table.row :key="$member->id">
                                <flux:table.cell >
                                    <flux:tooltip content="{{ $member->user->email }}">
                                        <div>{{ $member->user->full_name }}</div>
                                    </flux:tooltip>

                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap hidden sm:table-cell">
                                    {{ $member->created_at->toFormattedDayDateString() }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ $member->role->name }}
                                </flux:table.cell>


                                <flux:table.cell class="text-right space-x-1">
                                    @if($member->user->id !== Auth::user()->id)
                                    <flux:button
                                        class="cursor-pointer"
                                        size="sm"
                                        icon="trash"
                                        variant="danger"
                                        wire:click="delete({{ $member->user->id  }})"
                                        wire:confirm="Are you sure you want revoke this access?"
                                    />
                                    @endif
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
