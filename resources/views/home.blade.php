@extends('layouts.app')

@section('title', 'Home - GMO Properties')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Image with Diagonal Overlay -->
            <div class="relative h-96 lg:h-[500px] diagonal-frame">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop" 
                     alt="Modern Building" 
                     class="w-full h-full object-cover">
            </div>

            <!-- Right Side - Content -->
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="gold-accent-line"></div>
                    <div class="flex-1">
                        <h1 class="text-5xl lg:text-7xl font-bold">
                            <span class="text-black">Welcome to</span><br>
                            <span class="text-gmo-gold">GMO Properties</span>
                        </h1>
                    </div>
                </div>
                
                <div class="pl-5 text-gray-700 max-w-lg space-y-4">
                    <p class="leading-relaxed text-lg">
                        GMO Properties is a leading property management company specializing in student accommodation and residential properties across South Africa. 
                        We provide comprehensive property management services with a focus on transparency, efficiency, and exceptional tenant experiences.
                    </p>
                    <p class="leading-relaxed">
                        Our innovative multi-tenant SaaS platform serves property companies, property managers, and thousands of tenants with cutting-edge technology 
                        and personalized service. We combine years of industry experience with modern digital solutions to revolutionize property management.
                    </p>
                    <div class="pt-4 space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-gmo-gold rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-700"><strong class="text-black">100+ Properties</strong> under management across South Africa</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-gmo-gold rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-700"><strong class="text-black">Multi-Tenant Platform</strong> serving property companies and tenants</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-gmo-gold rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-700"><strong class="text-black">POPIA Compliant</strong> with secure data management and privacy protection</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-gmo-gold rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-700"><strong class="text-black">AI-Powered Solutions</strong> for efficient tenant screening and document processing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-black mb-4">Our Services</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Comprehensive property management solutions tailored to your needs</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('about') }}" class="group">
                <div class="bg-white p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="gold-square mb-4">01</div>
                    <h3 class="text-xl font-bold text-black mb-2 group-hover:text-gmo-gold transition-colors">About Us</h3>
                    <p class="text-gray-600">Learn about our company, mission, values, and the team behind GMO Properties.</p>
                </div>
            </a>
            
            <a href="{{ route('project') }}" class="group">
                <div class="bg-white p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="gold-square mb-4">02</div>
                    <h3 class="text-xl font-bold text-black mb-2 group-hover:text-gmo-gold transition-colors">Our Projects</h3>
                    <p class="text-gray-600">Explore our portfolio of managed properties and see our work in action.</p>
                </div>
            </a>
            
            <a href="{{ route('contact') }}" class="group">
                <div class="bg-white p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="gold-square mb-4">03</div>
                    <h3 class="text-xl font-bold text-black mb-2 group-hover:text-gmo-gold transition-colors">Contact Us</h3>
                    <p class="text-gray-600">Get in touch with our team to discuss your property management needs.</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gmo-gold mb-6">Why Choose GMO Properties?</h2>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">✓</div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Technology-Driven Solutions</h3>
                            <p class="text-gray-700">Our state-of-the-art platform streamlines property management with automated processes, real-time reporting, and seamless tenant communication.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">✓</div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Expert Team</h3>
                            <p class="text-gray-700">Our experienced professionals bring years of expertise in property management, real estate, and technology to deliver exceptional results.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">✓</div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Transparent Operations</h3>
                            <p class="text-gray-700">We believe in complete transparency with detailed financial reporting, clear communication, and honest business practices.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="gold-square flex-shrink-0">✓</div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Comprehensive Support</h3>
                            <p class="text-gray-700">From tenant onboarding to maintenance management, we provide end-to-end support for all your property management needs.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative h-96 lg:h-[500px] diagonal-frame">
                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop" 
                     alt="Modern Property Management" 
                     class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>
@endsection
