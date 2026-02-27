<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - GMO Properties</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gmo-black text-gmo-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gmo-gold">GMO Properties</h1>
                <p class="text-sm text-gray-400 mt-1">Admin Portal</p>
            </div>
            <nav class="mt-8">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-6 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-gmo-gold' : ''); ?>">
                    Dashboard
                </a>
                <a href="<?php echo e(route('admin.companies.index')); ?>" class="block px-6 py-3 hover:bg-gray-800 <?php echo e(request()->routeIs('admin.companies.*') ? 'bg-gray-800 border-l-4 border-gmo-gold' : ''); ?>">
                    Companies
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600"><?php echo e(Auth::user()->name); ?></span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-sm text-gray-600 hover:text-gmo-gold">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/user-1/Desktop/My Buuild /gmoproperties/resources/views/layouts/admin.blade.php ENDPATH**/ ?>