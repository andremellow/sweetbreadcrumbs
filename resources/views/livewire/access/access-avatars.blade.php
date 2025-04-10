@php

    $plus = $this->members->toArray()['total'] - count($this->members);
@endphp
<flux:avatar.group>
    @forelse ($this->members as $member)
        <flux:avatar size="sm" as="button" tooltip circle src="{{ $member->user->avatar }}" name="{{ $member->user->full_name  }}" color="auto"/>
    @empty
        <flux:avatar size="sm" as="button" circle>+</flux:avatar>
    @endforelse


    <flux:avatar size="sm" dusk="open-member-access-modal" as="button" color="green" circle>{{ $plus > 0 ? $plus : ''  }}+</flux:avatar>
</flux:avatar.group>
