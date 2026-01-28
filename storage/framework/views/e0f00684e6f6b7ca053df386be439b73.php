<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Loja')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?php echo e(route('shop.index')); ?>" class="font-bold text-xl text-indigo-600">Minha Loja</a>
            <div class="flex space-x-4">
                <a href="<?php echo e(route('shop.index')); ?>" class="text-gray-600 hover:text-gray-900">Início</a>
                <a href="<?php echo e(route('cart.index')); ?>" class="text-gray-600 hover:text-gray-900">Carrinho</a>
            </div>
        </div>
    </nav>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12 text-center">
        <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?></p>
    </footer>
</body>
</html><?php /**PATH C:\Users\Isabella\Downloads\ecommerce-roupas-atualizado\resources\views/layouts/shop.blade.php ENDPATH**/ ?>