<section class="w-full">
    <?php if (isset($component)) { $__componentOriginal98df7be4d2d83ea3b787e656b1f8d7eb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal98df7be4d2d83ea3b787e656b1f8d7eb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.heading','data' => ['heading' => ''.e(__('Meetings')).'','subheading' => ''.__('You don\'t need to remember everything.').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => ''.e(__('Meetings')).'','subheading' => ''.__('You don\'t need to remember everything.').'']); ?>
        <?php if (isset($component)) { $__componentOriginal1db8c57e729d67f7d4103875cf3230cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1db8c57e729d67f7d4103875cf3230cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::modal.trigger','data' => ['name' => 'meeting-form-modal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::modal.trigger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'meeting-form-modal']); ?>
            <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('Create meeting')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1db8c57e729d67f7d4103875cf3230cb)): ?>
<?php $attributes = $__attributesOriginal1db8c57e729d67f7d4103875cf3230cb; ?>
<?php unset($__attributesOriginal1db8c57e729d67f7d4103875cf3230cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1db8c57e729d67f7d4103875cf3230cb)): ?>
<?php $component = $__componentOriginal1db8c57e729d67f7d4103875cf3230cb; ?>
<?php unset($__componentOriginal1db8c57e729d67f7d4103875cf3230cb); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal98df7be4d2d83ea3b787e656b1f8d7eb)): ?>
<?php $attributes = $__attributesOriginal98df7be4d2d83ea3b787e656b1f8d7eb; ?>
<?php unset($__attributesOriginal98df7be4d2d83ea3b787e656b1f8d7eb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal98df7be4d2d83ea3b787e656b1f8d7eb)): ?>
<?php $component = $__componentOriginal98df7be4d2d83ea3b787e656b1f8d7eb; ?>
<?php unset($__componentOriginal98df7be4d2d83ea3b787e656b1f8d7eb); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal7fb0fdf5d970c3d030472604affe84c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7fb0fdf5d970c3d030472604affe84c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workstreams.layout','data' => ['workstream' => $workstream]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workstreams.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['workstream' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($workstream)]); ?>
        <?php if (isset($component)) { $__componentOriginalcfd5323c6a1dc4619596c94ea9002787 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfd5323c6a1dc4619596c94ea9002787 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.filter-form','data' => ['wire:submit' => 'applyFilter','isFiltred' => $this->isFiltred]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.filter-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:submit' => 'applyFilter','isFiltred' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->isFiltred)]); ?>
            <?php if (isset($component)) { $__componentOriginal38a59d99477b73bbaed546b871345db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38a59d99477b73bbaed546b871345db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.filter-column','data' => ['span' => '3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.filter-column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['span' => '3']); ?>
                <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['wire:model' => 'search','label' => __('Name or Description'),'type' => 'text']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'search','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Name or Description')),'type' => 'text']); ?>
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
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal38a59d99477b73bbaed546b871345db5)): ?>
<?php $attributes = $__attributesOriginal38a59d99477b73bbaed546b871345db5; ?>
<?php unset($__attributesOriginal38a59d99477b73bbaed546b871345db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal38a59d99477b73bbaed546b871345db5)): ?>
<?php $component = $__componentOriginal38a59d99477b73bbaed546b871345db5; ?>
<?php unset($__componentOriginal38a59d99477b73bbaed546b871345db5); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal38a59d99477b73bbaed546b871345db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38a59d99477b73bbaed546b871345db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.filter-column','data' => ['span' => '2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.filter-column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['span' => '2']); ?>
                <?php if (isset($component)) { $__componentOriginal9739cba0311dcc8ecd1672ffe6699a8a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9739cba0311dcc8ecd1672ffe6699a8a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::date-picker.index','data' => ['wire:model' => 'dateRange','label' => __('Date'),'mode' => 'range','withPresets' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::date-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'dateRange','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Date')),'mode' => 'range','with-presets' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9739cba0311dcc8ecd1672ffe6699a8a)): ?>
<?php $attributes = $__attributesOriginal9739cba0311dcc8ecd1672ffe6699a8a; ?>
<?php unset($__attributesOriginal9739cba0311dcc8ecd1672ffe6699a8a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9739cba0311dcc8ecd1672ffe6699a8a)): ?>
<?php $component = $__componentOriginal9739cba0311dcc8ecd1672ffe6699a8a; ?>
<?php unset($__componentOriginal9739cba0311dcc8ecd1672ffe6699a8a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal38a59d99477b73bbaed546b871345db5)): ?>
<?php $attributes = $__attributesOriginal38a59d99477b73bbaed546b871345db5; ?>
<?php unset($__attributesOriginal38a59d99477b73bbaed546b871345db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal38a59d99477b73bbaed546b871345db5)): ?>
<?php $component = $__componentOriginal38a59d99477b73bbaed546b871345db5; ?>
<?php unset($__componentOriginal38a59d99477b73bbaed546b871345db5); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcfd5323c6a1dc4619596c94ea9002787)): ?>
<?php $attributes = $__attributesOriginalcfd5323c6a1dc4619596c94ea9002787; ?>
<?php unset($__attributesOriginalcfd5323c6a1dc4619596c94ea9002787); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcfd5323c6a1dc4619596c94ea9002787)): ?>
<?php $component = $__componentOriginalcfd5323c6a1dc4619596c94ea9002787; ?>
<?php unset($__componentOriginalcfd5323c6a1dc4619596c94ea9002787); ?>
<?php endif; ?>
        <!--[if BLOCK]><![endif]--><?php if(count($meetings) > 0): ?>
            <?php if (isset($component)) { $__componentOriginal0b7b59ffe319c08b3cdaadf828652f7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b7b59ffe319c08b3cdaadf828652f7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.meetings.table','data' => ['meetings' => $meetings,'sortBy' => $sortBy,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('meetings.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['meetings' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($meetings),'sortBy' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortBy),'sortDirection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b7b59ffe319c08b3cdaadf828652f7d)): ?>
<?php $attributes = $__attributesOriginal0b7b59ffe319c08b3cdaadf828652f7d; ?>
<?php unset($__attributesOriginal0b7b59ffe319c08b3cdaadf828652f7d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b7b59ffe319c08b3cdaadf828652f7d)): ?>
<?php $component = $__componentOriginal0b7b59ffe319c08b3cdaadf828652f7d; ?>
<?php unset($__componentOriginal0b7b59ffe319c08b3cdaadf828652f7d); ?>
<?php endif; ?>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginala923841762d5eb966257be6d69e2f100 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala923841762d5eb966257be6d69e2f100 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-no-data','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-no-data'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala923841762d5eb966257be6d69e2f100)): ?>
<?php $attributes = $__attributesOriginala923841762d5eb966257be6d69e2f100; ?>
<?php unset($__attributesOriginala923841762d5eb966257be6d69e2f100); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala923841762d5eb966257be6d69e2f100)): ?>
<?php $component = $__componentOriginala923841762d5eb966257be6d69e2f100; ?>
<?php unset($__componentOriginala923841762d5eb966257be6d69e2f100); ?>
<?php endif; ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7fb0fdf5d970c3d030472604affe84c4)): ?>
<?php $attributes = $__attributesOriginal7fb0fdf5d970c3d030472604affe84c4; ?>
<?php unset($__attributesOriginal7fb0fdf5d970c3d030472604affe84c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7fb0fdf5d970c3d030472604affe84c4)): ?>
<?php $component = $__componentOriginal7fb0fdf5d970c3d030472604affe84c4; ?>
<?php unset($__componentOriginal7fb0fdf5d970c3d030472604affe84c4); ?>
<?php endif; ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('meeting.meeting-modal', ['workstream' => $workstream]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1121291996-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section><?php /**PATH /Users/andrepiresdemello/Herd/SweetBreadCrumbs/resources/views/livewire/meeting/list-meetings.blade.php ENDPATH**/ ?>