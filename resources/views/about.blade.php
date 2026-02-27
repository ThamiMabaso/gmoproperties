@extends('layouts.app')

@section('title', 'About Us - GMO Properties')

@section('content')
<!-- About Us Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold mb-4">ABOUT US</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Leading property management solutions across South Africa</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
            <!-- Left Side - Content -->
            <div class="space-y-6">
                <div class="space-y-4 text-gray-700">
                    <p class="leading-relaxed text-lg">
                        GMO Properties is a dynamic property management company that specializes in providing comprehensive 
                        property management solutions for student accommodation and residential properties across South Africa. 
                        We combine innovative technology with personalized service to deliver exceptional results for property 
                        owners, managers, and tenants.
                    </p>
                    <p class="leading-relaxed">
                        Founded with a vision to revolutionize property management in South Africa, GMO Properties has 
                        grown to serve property companies, property managers, and thousands of tenants. Our multi-tenant 
                        SaaS platform enables property companies to manage their entire portfolio efficiently while 
                        providing tenants with a modern, user-friendly experience.
                    </p>
                    <p class="leading-relaxed">
                        Our team brings years of experience in property management, real estate, and technology to create 
                        a seamless experience for all stakeholders. We understand the unique challenges of managing student 
                        accommodation and residential properties, and we've built our platform to address these challenges 
                        effectively.
                    </p>
                </div>
            </div>

            <!-- Right Side - Image with Diagonal Frame -->
            <div class="relative h-96 lg:h-[500px] diagonal-frame">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop" 
                     alt="Modern Buildings" 
                     class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Our Values Section -->
        <div class="mb-16">
            <h3 class="text-3xl font-bold text-gmo-gold mb-8 text-center">Our Core Values</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="gold-square mx-auto mb-4">T</div>
                    <h4 class="font-bold text-xl mb-3">Transparency</h4>
                    <p class="text-gray-700">We believe in complete transparency with detailed financial reporting, clear communication, and honest business practices. Our clients always know where they stand.</p>
                </div>
                <div class="text-center">
                    <div class="gold-square mx-auto mb-4">E</div>
                    <h4 class="font-bold text-xl mb-3">Efficiency</h4>
                    <p class="text-gray-700">Our technology-driven approach streamlines operations, reduces manual work, and ensures quick response times for all property management needs.</p>
                </div>
                <div class="text-center">
                    <div class="gold-square mx-auto mb-4">E</div>
                    <h4 class="font-bold text-xl mb-3">Excellence</h4>
                    <p class="text-gray-700">We are committed to delivering exceptional service quality in every aspect of property management, from tenant relations to financial reporting.</p>
                </div>
            </div>
        </div>

        <!-- What We Do Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative h-96 lg:h-[500px] diagonal-frame order-2 lg:order-1">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop" 
                     alt="Property Management Services" 
                     class="w-full h-full object-cover">
            </div>
            <div class="space-y-6 order-1 lg:order-2">
                <h3 class="text-3xl font-bold text-gmo-gold mb-6">What We Do</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">01</div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Property Management</h4>
                            <p class="text-gray-700">Comprehensive management of student accommodation and residential properties, including tenant relations, maintenance, and compliance.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">02</div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Financial Management</h4>
                            <p class="text-gray-700">Complete financial tracking, invoicing, payment processing, and detailed reporting for property owners and managers.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">03</div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Tenant Services</h4>
                            <p class="text-gray-700">Modern tenant portal for applications, payments, maintenance requests, and communication, providing a seamless experience.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">04</div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Technology Platform</h4>
                            <p class="text-gray-700">Multi-tenant SaaS platform that enables property companies to scale efficiently while maintaining personalized service.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Commitment Section -->
        <div class="mt-16 bg-gray-50 p-8 rounded-lg">
            <h3 class="text-3xl font-bold text-gmo-gold mb-6 text-center">Our Commitment</h3>
            <div class="max-w-4xl mx-auto text-center space-y-4">
                <p class="text-gray-700 leading-relaxed text-lg">
                    At GMO Properties, we believe in transparency, efficiency, and building lasting relationships with 
                    our clients. Our commitment to excellence drives everything we do, from tenant onboarding to 
                    financial reporting and maintenance management.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    We are dedicated to providing property companies with the tools and support they need to succeed, 
                    while ensuring tenants enjoy a modern, convenient living experience. Our platform is designed to 
                    grow with your business, adapting to your needs as you scale.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    With over 100+ properties under management and a team of experienced professionals, GMO Properties 
                    is your trusted partner in property management across South Africa.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
