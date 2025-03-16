<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['task']));

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

foreach (array_filter((['task']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div wire:loading.class="hidden">
    <!--[if BLOCK]><![endif]--><?php if($task->is_completed): ?>
        <?php if (isset($component)) { $__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.circle-check','data' => ['class' => 'text-green-500 dark:text-green-300','wire:click' => 'open('.e($task->id).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.circle-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-green-500 dark:text-green-300','wire:click' => 'open('.e($task->id).')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41)): ?>
<?php $attributes = $__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41; ?>
<?php unset($__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41)): ?>
<?php $component = $__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41; ?>
<?php unset($__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41); ?>
<?php endif; ?>
    <?php else: ?>
        <div class="group" >
            <?php if (isset($component)) { $__componentOriginal8ebdea7bd9875e20ef091d4697bab605 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ebdea7bd9875e20ef091d4697bab605 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.circle','data' => ['class' => 'text-black-500 dark:text-black-300 hover:text-green-500 dark:hover:text-green-300 group-hover:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-black-500 dark:text-black-300 hover:text-green-500 dark:hover:text-green-300 group-hover:hidden']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ebdea7bd9875e20ef091d4697bab605)): ?>
<?php $attributes = $__attributesOriginal8ebdea7bd9875e20ef091d4697bab605; ?>
<?php unset($__attributesOriginal8ebdea7bd9875e20ef091d4697bab605); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ebdea7bd9875e20ef091d4697bab605)): ?>
<?php $component = $__componentOriginal8ebdea7bd9875e20ef091d4697bab605; ?>
<?php unset($__componentOriginal8ebdea7bd9875e20ef091d4697bab605); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.circle-check','data' => ['wire:loading.class' => 'hidden','class' => 'text-green-500 cursor-pointer dark:text-green-300 hidden group-hover:block','wire:click' => 'close('.e($task->id).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.circle-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:loading.class' => 'hidden','class' => 'text-green-500 cursor-pointer dark:text-green-300 hidden group-hover:block','wire:click' => 'close('.e($task->id).')']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41)): ?>
<?php $attributes = $__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41; ?>
<?php unset($__attributesOriginal80f3db1f8a38bc71fa0c843bdb76ee41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41)): ?>
<?php $component = $__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41; ?>
<?php unset($__componentOriginal80f3db1f8a38bc71fa0c843bdb76ee41); ?>
<?php endif; ?>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /Users/andrepiresdemello/Herd/SweetBreadCrumbs/resources/views/components/tasks/complete-button.blade.php ENDPATH**/ ?>