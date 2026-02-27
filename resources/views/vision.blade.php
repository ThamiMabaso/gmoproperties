@extends('layouts.app')

@section('title', 'Vision & Mission - GMO Properties')

@section('content')
<!-- Vision & Mission Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Side - Content -->
            <div class="space-y-12">
                <!-- Vision -->
                <div>
                    <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold mb-6">OUR VISION</h2>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">01</div>
                        <div class="text-gray-700 leading-relaxed">
                            <p>
                                To become the leading property management platform in South Africa, recognized for innovation, 
                                reliability, and exceptional service. We envision a future where property management is seamless, 
                                transparent, and accessible to all stakeholders through cutting-edge technology and personalized 
                                service. Our vision extends beyond property management to creating thriving communities where 
                                tenants feel at home and property owners achieve their investment goals.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mission -->
                <div>
                    <h2 class="text-5xl lg:text-6xl font-bold text-gmo-gold mb-6">MISSION</h2>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">02</div>
                        <div class="text-gray-700 leading-relaxed">
                            <p>
                                Our mission is to empower property companies, property managers, and tenants with a comprehensive, 
                                user-friendly platform that simplifies property management while maintaining the highest standards 
                                of service, security, and compliance. We are committed to continuous innovation, building strong 
                                relationships, and delivering value that exceeds expectations. Through our multi-tenant SaaS platform, 
                                we enable property companies to scale efficiently while providing tenants with a modern, convenient 
                                experience that enhances their living environment.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="relative h-96 lg:h-[600px] diagonal-frame">
                <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=800&h=600&fit=crop" 
                     alt="City Skyline" 
                     class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>
@endsection
