<section class="w-full">
    <?php if (isset($component)) { $__componentOriginalab534138c9829724ca0c77b48d355a01 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab534138c9829724ca0c77b48d355a01 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workstreams.heading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workstreams.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab534138c9829724ca0c77b48d355a01)): ?>
<?php $attributes = $__attributesOriginalab534138c9829724ca0c77b48d355a01; ?>
<?php unset($__attributesOriginalab534138c9829724ca0c77b48d355a01); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab534138c9829724ca0c77b48d355a01)): ?>
<?php $component = $__componentOriginalab534138c9829724ca0c77b48d355a01; ?>
<?php unset($__componentOriginalab534138c9829724ca0c77b48d355a01); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalcfd5323c6a1dc4619596c94ea9002787 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfd5323c6a1dc4619596c94ea9002787 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.filter-form','data' => ['wire:submit' => 'applyFilter','isFiltred' => $this->isFiltred,'@reset' => 'console.log(\'please reset the form\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.filter-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:submit' => 'applyFilter','isFiltred' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->isFiltred),'@reset' => 'console.log(\'please reset the form\')']); ?>
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
            <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['wire:model' => 'name','label' => __('Name'),'type' => 'text']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'name','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Name')),'type' => 'text']); ?>
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
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('priority-dropdown', ['wire:model.live' => 'priorityId']);

$__html = app('livewire')->mount($__name, $__params, 'lw-3497219329-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.filter-column','data' => ['span' => '1','class' => 'hidden sm:flex']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.filter-column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['span' => '1','class' => 'hidden sm:flex']); ?>
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
    <!--[if BLOCK]><![endif]--><?php if(count($workstreams) > 0): ?>
        <?php if (isset($component)) { $__componentOriginalfabb8baa1963a4b2eb0847eaf293f20f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfabb8baa1963a4b2eb0847eaf293f20f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.workstreams.table','data' => ['workstreams' => $workstreams,'sortBy' => $sortBy,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('workstreams.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['workstreams' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($workstreams),'sortBy' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortBy),'sortDirection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfabb8baa1963a4b2eb0847eaf293f20f)): ?>
<?php $attributes = $__attributesOriginalfabb8baa1963a4b2eb0847eaf293f20f; ?>
<?php unset($__attributesOriginalfabb8baa1963a4b2eb0847eaf293f20f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfabb8baa1963a4b2eb0847eaf293f20f)): ?>
<?php $component = $__componentOriginalfabb8baa1963a4b2eb0847eaf293f20f; ?>
<?php unset($__componentOriginalfabb8baa1963a4b2eb0847eaf293f20f); ?>
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
</section><?php /**PATH /Users/andrepiresdemello/Herd/SweetBreadCrumbs/resources/views/livewire/workstream/list-workstreams.blade.php ENDPATH**/ ?>