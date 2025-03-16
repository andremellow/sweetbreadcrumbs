<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['span' => 3]));

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

foreach (array_filter((['span' => 3]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$colSpanClasses = [
    1 => 'sm:col-span-1',
    2 => 'sm:col-span-2',
    3 => 'sm:col-span-3',
    4 => 'sm:col-span-4',
    5 => 'sm:col-span-5',
];
?>

<div <?php echo e($attributes->merge(['class' => $colSpanClasses[$span] ?? 'sm:col-span-3'])); ?>>
    <?php echo e($slot); ?>

</div>
<?php /**PATH /Users/andrepiresdemello/Herd/SweetBreadCrumbs/resources/views/components/form/filter-column.blade.php ENDPATH**/ ?>