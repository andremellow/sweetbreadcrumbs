<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([ 'tasks', 'sortBy', 'sortDirection' ]));

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

foreach (array_filter(([ 'tasks', 'sortBy', 'sortDirection' ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div>
        <!--[if BLOCK]><![endif]--><?php if(count($tasks) > 0): ?>
            <div class="divide-y-1 divide-natural-200  dark:divide-white/20">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex py-2 items-center" wire:key="task-<?php echo e($task->id); ?>" wire:loading.class="opacity-50" wire:target="open, close" wire:target="close">
                    <div class="mr-5 w-5">
                        <?php if (isset($component)) { $__componentOriginalfa906b7cbe5a4e8e9893e6a80717a2be = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa906b7cbe5a4e8e9893e6a80717a2be = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tasks.complete-button','data' => ['task' => $task]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tasks.complete-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['task' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($task)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfa906b7cbe5a4e8e9893e6a80717a2be)): ?>
<?php $attributes = $__attributesOriginalfa906b7cbe5a4e8e9893e6a80717a2be; ?>
<?php unset($__attributesOriginalfa906b7cbe5a4e8e9893e6a80717a2be); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfa906b7cbe5a4e8e9893e6a80717a2be)): ?>
<?php $component = $__componentOriginalfa906b7cbe5a4e8e9893e6a80717a2be; ?>
<?php unset($__componentOriginalfa906b7cbe5a4e8e9893e6a80717a2be); ?>
<?php endif; ?>
                    </div>
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <div><?php echo e($task->name); ?></div>
                            <div><?php if (isset($component)) { $__componentOriginalb3f3930a96171a12366c9551b2dd3c07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb3f3930a96171a12366c9551b2dd3c07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.priority-badge','data' => ['priority' => $task->priority,'size' => 'sm','iconOnly' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('priority-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['priority' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($task->priority),'size' => 'sm','iconOnly' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb3f3930a96171a12366c9551b2dd3c07)): ?>
<?php $attributes = $__attributesOriginalb3f3930a96171a12366c9551b2dd3c07; ?>
<?php unset($__attributesOriginalb3f3930a96171a12366c9551b2dd3c07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb3f3930a96171a12366c9551b2dd3c07)): ?>
<?php $component = $__componentOriginalb3f3930a96171a12366c9551b2dd3c07; ?>
<?php unset($__componentOriginalb3f3930a96171a12366c9551b2dd3c07); ?>
<?php endif; ?></div>
                        </div>
                        <div class="w-full flex justify-between mt-2">
                            <!--[if BLOCK]><![endif]--><?php if($task->due_date): ?>
                                <?php if (isset($component)) { $__componentOriginal4cc377eda9b63b796b6668ee7832d023 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4cc377eda9b63b796b6668ee7832d023 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::badge.index','data' => ['size' => 'sm','icon' => 'calendar-days']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','icon' => 'calendar-days']); ?><?php echo e($task->due_date->format('m/d/Y')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4cc377eda9b63b796b6668ee7832d023)): ?>
<?php $attributes = $__attributesOriginal4cc377eda9b63b796b6668ee7832d023; ?>
<?php unset($__attributesOriginal4cc377eda9b63b796b6668ee7832d023); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4cc377eda9b63b796b6668ee7832d023)): ?>
<?php $component = $__componentOriginal4cc377eda9b63b796b6668ee7832d023; ?>
<?php unset($__componentOriginal4cc377eda9b63b796b6668ee7832d023); ?>
<?php endif; ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($task->is_late): ?>
                            <?php if (isset($component)) { $__componentOriginal4cc377eda9b63b796b6668ee7832d023 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4cc377eda9b63b796b6668ee7832d023 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::badge.index','data' => ['size' => 'sm','color' => 'red','icon' => 'clock-alert']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'red','icon' => 'clock-alert']); ?><?php echo e(__('Late')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4cc377eda9b63b796b6668ee7832d023)): ?>
<?php $attributes = $__attributesOriginal4cc377eda9b63b796b6668ee7832d023; ?>
<?php unset($__attributesOriginal4cc377eda9b63b796b6668ee7832d023); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4cc377eda9b63b796b6668ee7832d023)): ?>
<?php $component = $__componentOriginal4cc377eda9b63b796b6668ee7832d023; ?>
<?php unset($__componentOriginal4cc377eda9b63b796b6668ee7832d023); ?>
<?php endif; ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        </div>
                    </div>
                    
                        
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <?php else: ?>
            <div class="h-3/4 flex justify-center items-center">
                <div class="text-center">No task logged for this workstream</div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /Users/andrepiresdemello/Herd/SweetBreadCrumbs/resources/views/livewire/workstream/list-tasks-card.blade.php ENDPATH**/ ?>