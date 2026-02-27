<?php $__env->startSection('title', 'Companies'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Companies</h2>
        <a href="<?php echo e(route('admin.companies.create')); ?>" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
            Add New Company
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($companies->count() > 0): ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($company->name); ?></div>
                                <?php if($company->slug): ?>
                                    <div class="text-sm text-gray-500"><?php echo e($company->slug); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($company->email ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($company->phone ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo e(ucfirst($company->subscription_plan)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($company->is_active): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($company->users_count ?? $company->users->count()); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($company->created_at->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?php echo e(route('admin.companies.show', $company)); ?>" class="text-gmo-gold hover:text-gmo-gold-dark mr-4">View</a>
                                <a href="<?php echo e(route('admin.companies.edit', $company)); ?>" class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($companies->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg">No companies found.</p>
            <a href="<?php echo e(route('admin.companies.create')); ?>" class="mt-4 inline-block px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                Add Your First Company
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/user-1/Desktop/My Buuild /gmoproperties/resources/views/admin/companies/index.blade.php ENDPATH**/ ?>