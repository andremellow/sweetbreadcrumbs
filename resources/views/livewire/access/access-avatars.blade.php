@php

    $plus = $this->members->toArray()['total'] - count($this->members);
@endphp
<flux:avatar.group>
    @forelse ($this->members as $member)
        <flux:avatar size="sm" as="button" tooltip circle name="{{ $member->user->full_name  }}" color="auto"/>
    @empty
        <flux:avatar size="sm" as="button" circle>+</flux:avatar>
    @endforelse

    @if($plus > 0)
    <flux:avatar size="sm" as="button" circle>{{ $plus  }}+</flux:avatar>
        @endif
</flux:avatar.group>
