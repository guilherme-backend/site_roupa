<?php $__env->startSection('title', 'Teste'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Teste de Recuperação</h1>
        
        <p class="mb-4">Se você está vendo isso, o Layout e a Rota estão funcionando!</p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($products)): ?>
            <p>Produtos carregados: <?php echo e($products->count()); ?></p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border p-4 rounded shadow">
                        <h2 class="font-bold"><?php echo e($product->name); ?></h2>
                        <p>R$ <?php echo e($product->base_price); ?></p>
                        <a href="<?php echo e(route('shop.show', $product->slug)); ?>" class="text-blue-500">Ver Produto</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-red-500">A variável $products não chegou na View.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.shop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Isabella\Downloads\ecommerce-roupas-atualizado\resources\views/shop/index.blade.php ENDPATH**/ ?>