<flux:modal 
    name="meeting-view-modal"
    wire:model.self="showMeetingViewModal"
    variant="flyout" 
    position="bottom"
>
    
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Meeting for {{ $name }}</flux:heading>
                <div>{{ $date ? $date->toDateString() : '' }}</div>
            </div>

            <div>
                {!! $description !!}
            </div>
            
        </div>
    
</flux:modal>