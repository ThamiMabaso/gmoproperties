<?php $__env->startSection('title', 'Our Projects - GMO Properties'); ?>

<?php $__env->startSection('content'); ?>
<!-- Our Project Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start mb-16">
            <!-- Left Side - Stacked Images -->
            <div class="space-y-6">
                <div class="relative h-80 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=800&fit=crop" 
                         alt="Modern Skyscrapers" 
                         class="w-full h-full object-cover">
                </div>
                <div class="relative h-80 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&h=800&fit=crop" 
                         alt="Modern House with Pool" 
                         class="w-full h-full object-cover">
                </div>
                <div class="relative h-80 diagonal-frame">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&h=800&fit=crop" 
                         alt="Modern House in Nature" 
                         class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="space-y-6">
                <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold">OUR PROJECT</h2>
                
                <div class="space-y-4 text-gray-700">
                    <p class="leading-relaxed">
                        GMO Properties manages a diverse portfolio of student accommodation and residential properties 
                        across South Africa. Our projects range from modern student residences to luxury residential 
                        complexes, each managed with the same level of dedication and professionalism.
                    </p>
                    <p class="leading-relaxed">
                        We work closely with property owners and developers to ensure that every property in our portfolio 
                        meets the highest standards of quality, safety, and tenant satisfaction. Our comprehensive 
                        management approach covers everything from tenant acquisition to maintenance, financial reporting, 
                        and compliance.
                    </p>
                    <p class="leading-relaxed">
                        Our platform enables property companies to scale their operations efficiently while maintaining 
                        personalized service. With over 100+ properties under management, we've proven our ability to 
                        deliver results that exceed expectations.
                    </p>
                </div>
            </div>
        </div>

        <!-- Project Services Section -->
        <div class="mb-16">
            <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold mb-8">OUR PROJECT</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="flex items-start space-x-4">
                    <div class="gold-square flex-shrink-0">01</div>
                    <div>
                        <h3 class="font-bold text-lg mb-2">Student Accommodation</h3>
                        <p class="text-gray-700">Specialized management for student housing with focus on safety, community, and academic success.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="gold-square flex-shrink-0">02</div>
                    <div>
                        <h3 class="font-bold text-lg mb-2">Residential Properties</h3>
                        <p class="text-gray-700">Comprehensive management for residential complexes and apartment buildings.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="gold-square flex-shrink-0">03</div>
                    <div>
                        <h3 class="font-bold text-lg mb-2">Property Technology</h3>
                        <p class="text-gray-700">Innovative SaaS platform for property management companies.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="gold-square flex-shrink-0">04</div>
                    <div>
                        <h3 class="font-bold text-lg mb-2">Financial Management</h3>
                        <p class="text-gray-700">Complete financial tracking, invoicing, and reporting solutions.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="gold-square flex-shrink-0">05</div>
                    <div>
                        <h3 class="font-bold text-lg mb-2">Maintenance Services</h3>
                        <p class="text-gray-700">Efficient maintenance ticket system with contractor management.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="gold-square flex-shrink-0">06</div>
                    <div>
                        <h3 class="font-bold text-lg mb-2">Tenant Services</h3>
                        <p class="text-gray-700">Modern tenant portal for applications, payments, and communication.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Large Bottom Image -->
        <div class="relative h-96 lg:h-[600px] mb-16 diagonal-frame">
            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&h=600&fit=crop" 
                 alt="Modern House in Nature" 
                 class="w-full h-full object-cover">
        </div>

        <!-- Testimonial Section -->
        <div class="bg-gmo-gold p-8 lg:p-12 text-black">
            <p class="text-lg lg:text-xl leading-relaxed max-w-4xl mx-auto text-center">
                "GMO Properties has transformed how we manage our student accommodation portfolio. The platform is intuitive, 
                the team is responsive, and our tenants love the modern experience. We've seen significant improvements in 
                efficiency and tenant satisfaction since partnering with GMO."
            </p>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/user-1/Desktop/My Buuild /gmoproperties/resources/views/project.blade.php ENDPATH**/ ?>