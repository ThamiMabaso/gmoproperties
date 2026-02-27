<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Companies</p>
                    <p class="text-2xl font-bold text-gmo-gold"><?php echo e($stats['total_companies']); ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?php echo e($stats['active_companies']); ?> active</p>
                </div>
                <div class="w-12 h-12 bg-gmo-gold bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gmo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Properties</p>
                    <p class="text-2xl font-bold text-gmo-gold"><?php echo e($stats['total_properties']); ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?php echo e($stats['total_units']); ?> units</p>
                </div>
                <div class="w-12 h-12 bg-gmo-gold bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gmo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-green-600">R <?php echo e(number_format($financialOverview['monthly_revenue'], 2)); ?></p>
                    <p class="text-xs text-gray-500 mt-1">Total: R <?php echo e(number_format($financialOverview['total_revenue'], 2)); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Tenants</p>
                    <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['total_tenants']); ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?php echo e($stats['pending_applications']); ?> pending</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Plans & Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Subscription Plans Breakdown -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Subscription Plans</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">Basic</p>
                            <p class="text-sm text-gray-500"><?php echo e($subscriptionStats['basic']); ?> companies</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gmo-gold">R <?php echo e(number_format($revenueByPlan['basic'], 2)); ?></p>
                            <p class="text-xs text-gray-500">/month</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">Professional</p>
                            <p class="text-sm text-gray-500"><?php echo e($subscriptionStats['professional']); ?> companies</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gmo-gold">R <?php echo e(number_format($revenueByPlan['professional'], 2)); ?></p>
                            <p class="text-xs text-gray-500">/month</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">Enterprise</p>
                            <p class="text-sm text-gray-500"><?php echo e($subscriptionStats['enterprise']); ?> companies</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gmo-gold">R <?php echo e(number_format($revenueByPlan['enterprise'], 2)); ?></p>
                            <p class="text-xs text-gray-500">/month</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <p class="font-bold">Total Monthly Revenue</p>
                            <p class="text-xl font-bold text-gmo-gold">R <?php echo e(number_format(array_sum($revenueByPlan), 2)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Financial Overview</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600">Pending Payments</p>
                        <p class="font-semibold text-yellow-600">R <?php echo e(number_format($financialOverview['pending_payments'], 2)); ?></p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600">Overdue Payments</p>
                        <p class="font-semibold text-red-600">R <?php echo e(number_format($financialOverview['overdue_payments'], 2)); ?></p>
                    </div>
                    <div class="pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <p class="font-medium">Total Outstanding</p>
                            <p class="text-lg font-bold text-red-600">R <?php echo e(number_format($financialOverview['pending_payments'] + $financialOverview['overdue_payments'], 2)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Subscriptions -->
    <?php if($expiringSoon->count() > 0): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-yellow-600">Subscriptions Expiring Soon (Next 30 Days)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $expiringSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.companies.show', $company)); ?>" class="text-gmo-gold hover:underline">
                                    <?php echo e($company->name); ?>

                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo e(ucfirst($company->subscription_plan)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo e($company->subscription_expires_at->format('M d, Y')); ?>

                                <span class="text-yellow-600">(<?php echo e($company->subscription_expires_at->diffForHumans()); ?>)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.companies.edit', $company)); ?>" class="text-gmo-gold hover:underline">Renew</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Companies -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Recent Companies</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.companies.show', $company)); ?>" class="text-gmo-gold hover:underline">
                                    <?php echo e($company->name); ?>

                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($company->email); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($company->is_active): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($company->created_at->format('M d, Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No companies found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Approvals -->
    <?php if($pendingCompanies->count() > 0): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Pending Approvals</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $pendingCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e($company->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($company->email); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($company->created_at->format('M d, Y')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.companies.show', $company)); ?>" class="text-gmo-gold hover:underline">Review</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/user-1/Desktop/My Buuild /gmoproperties/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>