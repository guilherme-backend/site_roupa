

<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <nav aria-label="Breadcrumb" class="mb-8">
            <ol role="list" class="flex items-center space-x-2">
                <li><a href="<?php echo e(route('shop.index')); ?>" class="text-gray-400 hover:text-gray-500">Loja</a></li>
                <li class="text-gray-300">/</li>
                <li><a href="<?php echo e(route('shop.category', $product->category->slug)); ?>" class="text-gray-400 hover:text-gray-500"><?php echo e($product->category->name); ?></a></li>
                <li class="text-gray-300">/</li>
                <li class="text-gray-900 font-medium truncate"><?php echo e($product->name); ?></li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <div class="flex flex-col-reverse">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->images && $product->images->count() > 1): ?>
                    <div class="hidden mt-6 w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                        <div class="grid grid-cols-4 gap-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button class="relative h-24 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-opacity-50 ring-indigo-500">
                                    <span class="sr-only">Ver imagem</span>
                                    <span class="absolute inset-0 rounded-md overflow-hidden">
                                        <img src="<?php echo e(Storage::url($image->image_path)); ?>" alt="" class="w-full h-full object-center object-cover">
                                    </span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="w-full aspect-w-1 aspect-h-1 rounded-lg overflow-hidden bg-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->primaryImage): ?>
                        <img src="<?php echo e(Storage::url($product->primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-center object-cover sm:rounded-lg">
                    <?php else: ?>
                        <div class="flex items-center justify-center h-96 bg-gray-100 text-gray-400">
                            Sem imagem
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900"><?php echo e($product->name); ?></h1>

                <div class="mt-3">
                    <h2 class="sr-only">Informações do produto</h2>
                    <p class="text-3xl text-gray-900">R$ <?php echo e(number_format($product->base_price, 2, ',', '.')); ?></p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Descrição</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        <p><?php echo e($product->description); ?></p>
                    </div>
                </div>

                <form class="mt-6" action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->variants && $product->variants->count() > 0): ?>
                        <div class="mt-8">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-medium text-gray-900">Opções</h2>
                            </div>

                            <div class="grid grid-cols-3 gap-3 sm:grid-cols-6 mt-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="border rounded-md py-3 px-3 flex items-center justify-center text-sm font-medium uppercase sm:flex-1 cursor-pointer focus:outline-none hover:bg-gray-50 <?php echo e($variant->stock_quantity == 0 ? 'opacity-50 cursor-not-allowed bg-gray-50' : 'bg-white shadow-sm text-gray-900'); ?>">
                                        <input type="radio" name="variant_id" value="<?php echo e($variant->id); ?>" class="sr-only" <?php echo e($variant->stock_quantity == 0 ? 'disabled' : ''); ?> required>
                                        <span id="variant-label-<?php echo e($variant->id); ?>">
                                            <?php echo e($variant->name); ?> 
                                        </span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="mt-8 flex items-center space-x-4">
                        <div class="w-24">
                            <label for="quantity" class="sr-only">Quantidade</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-3 text-center">
                        </div>
                        
                        <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </form>

                <div class="mt-10 border-t border-gray-200 pt-10">
                    <h3 class="text-sm font-medium text-gray-900">Detalhes Adicionais</h3>
                    <div class="mt-4 prose prose-sm text-gray-500">
                        <ul role="list">
                            <li>Categoria: <?php echo e($product->category->name); ?></li>
                            <li>Em estoque: Sim</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($relatedProducts) && $relatedProducts->count() > 0): ?>
            <div class="mt-16 border-t border-gray-200 pt-10">
                <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 mb-6">Você também pode gostar</h2>
                <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group relative">
                            <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($related->primaryImage): ?>
                                    <img src="<?php echo e(Storage::url($related->primaryImage->image_path)); ?>" alt="<?php echo e($related->name); ?>" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full w-full bg-gray-100 text-gray-400">Sem Foto</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="<?php echo e(route('shop.show', $related->slug)); ?>">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            <?php echo e($related->name); ?>

                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500"><?php echo e($related->category->name); ?></p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">R$ <?php echo e(number_format($related->base_price, 2, ',', '.')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.shop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Isabella\Downloads\ecommerce-roupas-atualizado\resources\views/shop/show.blade.php ENDPATH**/ ?>