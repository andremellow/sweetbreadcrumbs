<section class="w-full">
    @include('partials.organization-heading')

    <x-organization.layout :organization="$organization">

        <flux:card>
            <form  wire:submit="send">
                <div class="flex flex-col space-y-4 sm:flex-row sm:items-start sm:justify-between sm:space-x-2 sm:space-y-0">
                    <div class="w-full">
                        <flux:input wire:model="email" label="Email" type="email" />   
                    </div>
                    <div class='w-full sm:w-64'>
                        <livewire:role-dropdown wire:model="role_id" key="role-dropdown" />
                        <flux:error name="role_id"/>
                    </div>
                    <div class="sm:mt-6.5">
                        <flux:button type="submit" icon="send" variant="filled">Send</flux:button>
                    </div>
                </div>
            </form >
        <div class="mt-5">
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
                                    {{ $invite->sent_at }}
                                @endif
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $invite->role->name }}
                            </flux:table.cell>

                            
                            <flux:table.cell class="text-right">
                                <flux:button class="cursor-pointer" size="sm" icon="send" >Resend</flux:button>
                                <flux:button 
                                    class="cursor-pointer"
                                    size="sm"
                                    icon="trash" 
                                    variant="danger"
                                    wire:click="delete({{ $invite->id  }})"
                                    wire:confirm="Are you sure you want to cancel this invite?"
                                >Cancel</flux:button>
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

    </x-organization.layout>
</section>
