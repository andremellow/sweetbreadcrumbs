<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'selectableHeader' => null,
    'withConfirmation' => null,
    'weekNumbers' => null,
    'placeholder' => null,
    'withPresets' => null,
    'withInputs' => null,
    'clearable' => null,
    'withToday' => null,
    'presets' => null,
    'trigger' => null,
    'invalid' => null,
    'months' => null,
    'size' => null,
    'mode' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'selectableHeader' => null,
    'withConfirmation' => null,
    'weekNumbers' => null,
    'placeholder' => null,
    'withPresets' => null,
    'withInputs' => null,
    'clearable' => null,
    'withToday' => null,
    'presets' => null,
    'trigger' => null,
    'invalid' => null,
    'months' => null,
    'size' => null,
    'mode' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
// Support adding the .self modifier to the wire:model directive...
if (($wireModel = $attributes->wire('model')) && $wireModel->directive && ! $wireModel->hasModifier('self')) {
    unset($attributes[$wireModel->directive]);

    $wireModel->directive .= '.self';

    $attributes = $attributes->merge([$wireModel->directive => $wireModel->value]);
}

$months = $months ?? ($mode === 'range' ? 2 : 1);

$range = $mode === 'range';

$placeholder = $placeholder ?? ($range ? __('Select a date range') : __('Select a date'));

$invalid ??= ($name && $errors->has($name));

$class= Flux::classes()
    ->add('block min-w-0')
    // The below reverts styles added by Tailwind Forms plugin...
    ->add('border-0 p-0 bg-transparent')
    ;

$sizeClasses = match ($size) {
    '2xl' => $weekNumbers ? 'size-11 sm:size-14' : 'size-12 sm:size-12',
    'xl' => $weekNumbers ? 'size-11 sm:size-12' : 'size-12 sm:size-12',
    'lg' => $weekNumbers ? 'size-10 sm:size-11' : 'size-11 sm:size-11',
    default => $weekNumbers ? 'size-10 sm:size-10' : 'size-11 sm:size-10',
    'sm' => $weekNumbers ? 'size-10 sm:size-9' : 'size-11 sm:size-9',
};

if ($withPresets) {
    $presets = $presets ?? 'today yesterday thisWeek last7Days thisMonth yearToDate allTime';
}

$presetArrayOfStrings = (string) is_string($presets) ? explode(' ', $presets) : [];

$presetArray = array_map(function ($preset) {
    return Flux\DateRangePreset::from($preset);
}, $presetArrayOfStrings);
?>

<?php if (isset($component)) { $__componentOriginal33e2911d6f1e72999cb4ebd3c5d00431 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal33e2911d6f1e72999cb4ebd3c5d00431 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::with-field','data' => ['attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::with-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
    <ui-date-picker
        <?php echo e($attributes->class($class)); ?>

        data-flux-control
        data-flux-date-picker
        <?php if($mode): ?> mode="<?php echo e($mode); ?>" <?php endif; ?>
        months="1"
        sm:months="<?php echo e($months); ?>"
    >
        <?php if ($trigger === null): ?>
            <?php if (isset($component)) { $__componentOriginal7f2aac9338c48f15200a785f71a383f0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f2aac9338c48f15200a785f71a383f0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::date-picker.button','data' => ['placeholder' => $placeholder,'invalid' => $invalid,'size' => $size,'clearable' => $clearable]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::date-picker.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholder),'invalid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($invalid),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($size),'clearable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($clearable)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f2aac9338c48f15200a785f71a383f0)): ?>
<?php $attributes = $__attributesOriginal7f2aac9338c48f15200a785f71a383f0; ?>
<?php unset($__attributesOriginal7f2aac9338c48f15200a785f71a383f0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f2aac9338c48f15200a785f71a383f0)): ?>
<?php $component = $__componentOriginal7f2aac9338c48f15200a785f71a383f0; ?>
<?php unset($__componentOriginal7f2aac9338c48f15200a785f71a383f0); ?>
<?php endif; ?>
        <?php else: ?>
            <?php echo e($trigger); ?>

        <?php endif; ?>

        <dialog wire:ignore class="max-sm:max-h-full! rounded-xl shadow-xl sm:shadow-2xs max-sm:fixed! max-sm:inset-0! sm:backdrop:bg-transparent bg-white dark:bg-zinc-900 sm:border border-zinc-200 dark:border-white/10">
            <ui-calendar class="isolate relative grid sm:grid-cols-[auto_1fr] grid-rows-[auto_auto_auto_auto_auto]" wire:ignore>
                <?php if ($presets): ?>
                    <ui-calendar-presets class="row-span-full max-sm:hidden border-r border-zinc-200 dark:border-zinc-600">
                        <ui-radio-group class="flex flex-col gap-1 p-2 min-w-[120px]">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $presetArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <ui-radio
                                    value="<?php echo e($preset->value); ?>"
                                    class="text-sm font-medium text-zinc-600 dark:text-zinc-300 data-checked:bg-(--color-accent) data-checked:text-(--color-accent-foreground) px-2 py-1.5 whitespace-nowrap rounded-lg hover:bg-zinc-100 hover:text-zinc-800 dark:hover:bg-white/5 dark:hover:text-white"
                                ><?php echo e($preset->label()); ?></ui-radio>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ui-radio-group>
                    </ui-calendar-presets>
                <?php else: ?>
                    <div class="row-span-full"></div>
                <?php endif; ?>

                <?php if ($withInputs): ?>
                    <ui-calendar-inputs class="flex items-center p-2 border-b border-zinc-200 dark:border-white/10">
                        <?php if ($range): ?>
                            <div class="sm:px-2 flex items-center gap-4">
                                <div class="flex items-center gap-2"><span class="max-sm:hidden text-sm font-medium text-zinc-800 dark:text-white">Start</span> <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['type' => 'date','class' => 'w-[full] sm:w-[11.25rem]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','class' => 'w-[full] sm:w-[11.25rem]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?></div>
                                <div class="flex items-center gap-2"><span class="max-sm:hidden text-sm font-medium text-zinc-800 dark:text-white">End</span> <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['type' => 'date','class' => 'w-[full] sm:w-[11.25rem]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','class' => 'w-[full] sm:w-[11.25rem]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?></div>
                            </div>
                        <?php else: ?>
                            <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['type' => 'date','class' => 'w-full sm:w-[11.25rem]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','class' => 'w-full sm:w-[11.25rem]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
                        <?php endif; ?>
                    </ui-calendar-inputs>
                <?php endif; ?>

                <div class="relative">
                    <div class="z-10 absolute top-0 inset-x-0 p-2">
                        <header class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <?php if ($selectableHeader): ?>
                                    <ui-calendar-month display="short" class="font-medium text-sm text-zinc-800 dark:text-white">
                                        <select
                                            class="h-10 py-0 border-0 text-sm sm:h-8 appearance-none rounded-lg bg-zinc-100 dark:bg-white/10 dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white px-3 sm:pl-2 [background-position:_right_.25rem_center_!important] pr-[1.35rem] bg-[length:16px_16px] bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%2300000040%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] hover:bg-[length:16px_16px] hover:bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%231f2937%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] dark:bg-[length:16px_16px] dark:bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%23ffffff75%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] dark:hover:bg-[length:16px_16px] dark:hover:bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%23ffffff%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] bg-no-repeat"
                                        >
                                            <template>
                                                <option><slot></slot></option>
                                            </template>
                                        </select>
                                    </ui-calendar-month>

                                    <ui-calendar-year class="font-medium text-sm text-zinc-800 dark:text-white">
                                        <select
                                            class="h-10 py-0 border-0 text-sm sm:h-8 appearance-none rounded-lg bg-zinc-100 dark:bg-white/10 dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white px-3 sm:pl-2 [background-position:_right_.25rem_center_!important] pr-[1.35rem] bg-[length:16px_16px] bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%2300000040%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] hover:bg-[length:16px_16px] hover:bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%231f2937%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] dark:bg-[length:16px_16px] dark:bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%23ffffff75%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] dark:hover:bg-[length:16px_16px] dark:hover:bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2016%2016%22%20fill=%22%23ffffff%22%20class=%22size-4%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M4.22%206.22a.75.75%200%200%201%201.06%200L8%208.94l2.72-2.72a.75.75%200%201%201%201.06%201.06l-3.25%203.25a.75.75%200%200%201-1.06%200L4.22%207.28a.75.75%200%200%201%200-1.06Z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] bg-no-repeat"
                                        >
                                            <template>
                                                <option><slot></slot></option>
                                            </template>
                                        </select>
                                    </ui-calendar-year>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center">
                                <?php if ($withToday): ?>
                                    <ui-calendar-today class="size-10 sm:size-8 rounded-lg flex items-center justify-center text-zinc-400 hover:bg-zinc-100 hover:text-zinc-800 dark:hover:bg-white/5 dark:hover:text-white [&[disabled]]:opacity-50 [&[disabled]]:pointer-events-none" aria-label="Previous month">
                                        <div class="relative">
                                            <template name="today">
                                                <div class="cursor-default absolute inset-0 mt-[3px] flex items-center justify-center text-[.5625rem] font-semibold"><slot></slot></div>
                                            </template>

                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.75 2C5.94891 2 6.13968 2.07902 6.28033 2.21967C6.42098 2.36032 6.5 2.55109 6.5 2.75V4H13.5V2.75C13.5 2.55109 13.579 2.36032 13.7197 2.21967C13.8603 2.07902 14.0511 2 14.25 2C14.4489 2 14.6397 2.07902 14.7803 2.21967C14.921 2.36032 15 2.55109 15 2.75V4H15.25C15.9793 4 16.6788 4.28973 17.1945 4.80546C17.7103 5.32118 18 6.02065 18 6.75V15.25C18 15.9793 17.7103 16.6788 17.1945 17.1945C16.6788 17.7103 15.9793 18 15.25 18H4.75C4.02065 18 3.32118 17.7103 2.80546 17.1945C2.28973 16.6788 2 15.9793 2 15.25V6.75C2 6.02065 2.28973 5.32118 2.80546 4.80546C3.32118 4.28973 4.02065 4 4.75 4H5V2.75C5 2.55109 5.07902 2.36032 5.21967 2.21967C5.36032 2.07902 5.55109 2 5.75 2ZM4.75 6.5C4.06 6.5 3.5 7.06 3.5 7.75V15.25C3.5 15.94 4.06 16.5 4.75 16.5H15.25C15.94 16.5 16.5 15.94 16.5 15.25V7.75C16.5 7.06 15.94 6.5 15.25 6.5H4.75Z" fill="currentColor"/>
                                            </svg>
                                        </div>
                                    </ui-calendar-today>
                                <?php endif; ?>

                                <ui-calendar-previous class="size-10 sm:size-8 rounded-lg flex items-center justify-center text-zinc-400 hover:bg-zinc-100 hover:text-zinc-800 dark:hover:bg-white/5 dark:hover:text-white [&[disabled]]:opacity-50 [&[disabled]]:pointer-events-none" aria-label="Previous month">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"> <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" /> </svg>
                                </ui-calendar-previous>

                                <ui-calendar-next class="size-10 sm:size-8 rounded-lg flex items-center justify-center text-zinc-400 hover:bg-zinc-100 hover:text-zinc-800 dark:hover:bg-white/5 dark:hover:text-white [&[disabled]]:opacity-50 [&[disabled]]:pointer-events-none [&[disabled]_&]:text-zinc-400" aria-label="Next month">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"> <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /> </svg>
                                </ui-calendar-next>
                            </div>
                        </header>
                    </div>
                </div>

                <ui-calendar-months class="relative flex justify-center p-2 gap-4">
                    <template name="month">
                        <div>
                            <template name="heading">
                                <div class="<?php if($selectableHeader): ?> [[data-month]:first-of-type_&]:opacity-0 <?php endif; ?> mb-2 px-2 h-10 sm:h-8 flex items-center">
                                    <div class="font-medium text-sm text-zinc-800 dark:text-white"><slot></slot></div>
                                </div>
                            </template>

                            <table>
                                <thead>
                                    <tr class="flex w-full">
                                        <?php if ($weekNumbers): ?>
                                            <th scope="col" class="<?php echo e($sizeClasses); ?> text-sm font-medium text-zinc-500 dark:text-zinc-300 flex items-center"><div class="w-full">#</div></th>
                                        <?php endif; ?>

                                        <template name="weekday">
                                            <th scope="col" class="<?php echo e($sizeClasses); ?> text-sm font-medium text-zinc-500 dark:text-zinc-300 flex items-center"><div class="w-full"><slot></slot></div></th>
                                        </template>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template name="week">
                                        <tr class="flex w-full not-first-of-type:mt-1 [&:first-of-type_td[data-in-range]:not([data-selected]):first-child]:rounded-l-none [&:last-of-type_td[data-in-range]:not([data-selected]):last-child]:rounded-r-none">
                                            <?php if ($weekNumbers): ?>
                                                <template name="number">
                                                    <td class="p-0 relative <?php echo e($sizeClasses); ?> text-xs font-medium text-zinc-400 flex items-center justify-center">
                                                        <slot></slot>
                                                    </td>
                                                </template>
                                            <?php endif; ?>
                                            <template name="day">
                                                <?php if ($attributes->has('static')): ?>
                                                    <td class="p-0 data-unavailable:line-through data-in-range:bg-zinc-100 dark:data-in-range:bg-white/10 data-start:rounded-l-lg data-end:rounded-r-lg data-end-preview:rounded-r-lg first-of-type:rounded-l-lg last-of-type:rounded-r-lg [&[data-selected]+[data-selected]]:rounded-l-none">
                                                        <div class="relative <?php echo e($sizeClasses); ?> text-sm font-medium text-zinc-800 dark:text-white flex items-center justify-center rounded-lg [td[data-selected]:has(+td[data-selected])_&]:rounded-r-none [td[data-selected]+td[data-selected]_&]:rounded-l-none [td[data-selected]_&]:bg-[var(--color-accent)] [td[data-selected]_&]:text-[var(--color-accent-foreground)] [td[data-selected]_&[disabled]]:opacity-50 disabled:text-zinc-400 disabled:pointer-events-none disabled:cursor-default">
                                                            <div class="absolute inset-0 hidden [td[data-today]_&]:flex justify-center items-end"><div class="mb-1 size-1 rounded-full bg-zinc-800 dark:bg-white [td[data-selected]_&]:bg-white dark:[td[data-selected]_&]:bg-zinc-800"></div></div>
                                                            <slot></slot>
                                                        </div>
                                                    </td>
                                                <?php else: ?>
                                                    <td class="_max-sm:data-outside:opacity-0 p-0 data-unavailable:line-through data-in-range:bg-zinc-100 dark:data-in-range:bg-white/10 data-start:rounded-l-lg data-end:rounded-r-lg data-end-preview:rounded-r-lg first-of-type:rounded-l-lg last-of-type:rounded-r-lg [&[data-selected]+[data-selected]]:rounded-l-none [[data-in-range]:not([data-selected]):not([data-end-preview])+&[data-outside]]:bg-linear-to-r [&[data-outside]:has(+[data-in-range])]:bg-linear-to-l from-zinc-100 dark:from-white/10 from-1% [&[data-outside]:has(+[data-in-range][data-selected])]:bg-none!">
                                                        <ui-tooltip position="top">
                                                            <button type="button" class="<?php echo e($sizeClasses); ?> text-sm font-medium text-zinc-800 dark:text-white flex flex-col items-center justify-center rounded-lg hover:bg-zinc-800/5 dark:hover:bg-white/5 [td[data-selected]:has(+td[data-selected])_&]:rounded-r-none [td[data-selected]+td[data-selected]_&]:rounded-l-none [td[data-selected]_&]:bg-[var(--color-accent)] [td[data-selected]_&]:text-[var(--color-accent-foreground)] [td[data-selected]_&[disabled]]:opacity-50 disabled:text-zinc-400 disabled:pointer-events-none disabled:cursor-default [[readonly]_&]:pointer-events-none [[readonly]_&]:cursor-default [[readonly]_&]:bg-transparent">
                                                                <div class="relative">
                                                                    <div class="absolute inset-x-0 bottom-[-3px] hidden [td[data-today]_&]:flex justify-center items-end"><div class="size-1 rounded-full bg-zinc-800 dark:bg-white [td[data-selected]_&]:bg-white dark:[td[data-selected]_&]:bg-zinc-800"></div></div>

                                                                    <div><slot></slot></div>

                                                                    <template name="subtext">
                                                                        <div class="absolute inset-x-0 bottom-[-1rem] flex justify-center font-medium text-xs text-zinc-400 dark:text-zinc-500 [[data-date-variant='success']_&]:text-lime-600 dark:[[data-date-variant='success']_&]:text-lime-400 [[data-date-variant='warning']_&]:text-yellow-600 dark:[[data-date-variant='warning']_&]:text-yellow-400 [[data-date-variant='danger']_&]:text-rose-500 dark:[[data-date-variant='danger']_&]:text-rose-400">
                                                                            <slot></slot>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </button>

                                                            <template name="details">
                                                                <div popover="manual" class="relative py-2 px-2.5 rounded-md text-xs text-white font-medium bg-zinc-800 dark:bg-zinc-700 dark:border dark:border-white/10 p-0 overflow-visible">
                                                                    <slot></slot>
                                                                </div>
                                                            </template>
                                                        </ui-tooltip>
                                                    </td>
                                                <?php endif; ?>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </ui-calendar-months>

                <?php if ($presets): ?>
                    <ui-calendar-presets class="block sm:hidden px-4">
                        <select class="appearance-none w-full pl-3 pr-10 block h-10 py-2 text-sm rounded-lg shadow-2xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[9%] text-zinc-700 dark:text-zinc-300 has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400 disabled:shadow-none border border-zinc-200 border-b-zinc-300/80 dark:border-white/10" data-flux-control="" data-flux-select-native="" data-flux-group-target="">
                            <option value="" disabled="" selected="" class="placeholder">Choose predefined range...</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $presetArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($preset->value); ?>"><?php echo e($preset->label()); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </ui-calendar-presets>
                <?php endif; ?>

                <div class="<?php if (! ($withConfirmation)): ?> sm:hidden <?php endif; ?> p-4 sm:p-2 flex justify-end gap-2">
                    <ui-close>
                        <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'ghost']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost']); ?>Cancel <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                    </ui-close>

                    <ui-date-picker-select>
                        <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?>
                            <?php if ($range): ?>
                                <?php echo e(__('Select range')); ?>

                            <?php else: ?>
                                <?php echo e(__('Select date')); ?>

                            <?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                    </ui-date-picker-select>
                </div>
            </ui-calendar>
        </dialog>
    </ui-date-picker>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal33e2911d6f1e72999cb4ebd3c5d00431)): ?>
<?php $attributes = $__attributesOriginal33e2911d6f1e72999cb4ebd3c5d00431; ?>
<?php unset($__attributesOriginal33e2911d6f1e72999cb4ebd3c5d00431); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal33e2911d6f1e72999cb4ebd3c5d00431)): ?>
<?php $component = $__componentOriginal33e2911d6f1e72999cb4ebd3c5d00431; ?>
<?php unset($__componentOriginal33e2911d6f1e72999cb4ebd3c5d00431); ?>
<?php endif; ?>
<?php /**PATH /Users/andrepiresdemello/Herd/SweetBreadCrumbs/vendor/livewire/flux-pro/src/../stubs/resources/views/flux/date-picker/index.blade.php ENDPATH**/ ?>